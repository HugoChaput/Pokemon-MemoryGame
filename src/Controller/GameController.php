<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\GameOptions;
use App\Entity\GameUser;
use App\Entity\User;
use App\Repository\GameOptionsRepository;
use App\Repository\GameRepository;
use App\Repository\GameUserRepository;
use App\Repository\PokemonRepository;
use App\Repository\TypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class GameController extends AbstractController
{
    #[Route('/game', name: 'app_game')]
    public function index(PokemonRepository $pokemonRepository, TypeRepository $typeRepository, GameRepository $gameRepository, GameOptionsRepository $gameOptionsRepository, GameUserRepository $gameUserRepository): Response
    {
        $_SESSION['Difficulty']=[
            'showCardsAtStart' => $_POST['showCardsAtStart'],
            'timer' => $_POST['timer'],
            'pairsNumber' => $_POST['pairsNumber'],
            'triesNumber' => $_POST['pairsNumber'],
            'gameMode' => $_POST['gameMode']
        ];//Implémenter le $_POST['triesNumber']

        //Créer une liste de pokemon selon le mode de jeu
        switch ($_POST['gameMode'])
        {
            case 'pokemonPairs':
                //Récupère tous les pokemon
                $pokemonList=$pokemonRepository->findAll();
                //Randomise le tableau
                shuffle($pokemonList);
                //On garde seulement le nombre de pairs choisi
                array_splice($pokemonList, $_POST['pairsNumber'], count($pokemonList));
                //Ajoute les meme pokemon pour avoir des paires
                $pokemonList=array_merge($pokemonList, $pokemonList);
                //Randomise le tableau
                shuffle($pokemonList);
            break;
            
            case 'typePairs':
                $pokemonList=[]; //Initialisation du tableau pour l'utiliser dans le foreach
                $typeList=[];
                //Récupère tous les types
                $types=$typeRepository->findAll();
                //Randomise le tableau
                shuffle($types);
                //On garde seulement le nombre de pairs choisi
                array_splice($types, $_POST['pairsNumber'], count($types));
                
                //Récupération des pokemon pour chaque type
                foreach ($types as $type)
                {
                    //Récupère tous les pokemon du type courant et transforme le résultat en tableau
                    $pokemonListFromType=$type->getPokemon()->toArray();
                    //Randomise le tableau
                    shuffle($pokemonListFromType);
                    //On garde seulement 2 pokemon
                    array_splice($pokemonListFromType, 2, count($pokemonListFromType));
                    //Ajoute chaque pokemon à la liste finale
                    foreach ($pokemonListFromType as $pokemon)
                    {
                        array_push($pokemonList, $pokemon);
                        $typeListElement=['id' => $pokemon->getApiId(), 'type' => $type->getType()];
                        array_push($typeList, $typeListElement);
                    }
                }
                $_SESSION['typeList']=$typeList;
                //Randomise le tableau
                shuffle($pokemonList);
            break;

            case 'genPairs':
                $pokemonList=[]; //Initialisation du tableau pour l'utiliser dans le foreach
                //J'utilise le nombre de generations en dur par manque de temps
                $generations=[1,2,3,4,5,6,7,8];
                //Randomise le tableau
                shuffle($generations);
                //On garde seulement le nombre de pairs choisi
                array_splice($generations, $_POST['pairsNumber'], count($generations));
                
                //Récupération des pokemon pour chaque génération
                foreach ($generations as $generation)
                {
                    //Récupère tous les pokemon de la génération courante
                    $pokemonListFromGeneration=$pokemonRepository->findBy(['generation' => $generation]);
                    //Randomise le tableau
                    shuffle($pokemonListFromGeneration);
                    //On garde seulement 2 pokemon
                    array_splice($pokemonListFromGeneration, 2, count($pokemonListFromGeneration));
                    //Ajoute chaque pokemon à la liste finale
                    foreach ($pokemonListFromGeneration as $pokemon)
                    {
                        array_push($pokemonList, $pokemon);
                    }
                }
                //Randomise le tableau
                shuffle($pokemonList);
            break;
            
            default:
                /* Erreur */;
        }

        //Enregistrement en BDD des la partie
        //Game
        $game = new Game();
        $game->setName("Test");
        $gameRepository->save($game, true);
        $_SESSION['gameId']=$game->getId();

        //GameOptions
        $gameOptions = new GameOptions();
        if ($_POST['showCardsAtStart']=="showCardsAtStartYes")
        {
            $gameOptions->setOptionName('showCardsAtStart');
            $gameOptions->setGame($game);
            $gameOptionsRepository->save($gameOptions, true);
        }
        if ($_POST['timer']=="timerYes")
        {
            $gameOptions->setOptionName('timer');
            $gameOptions->setGame($game);
            $gameOptionsRepository->save($gameOptions, true);
        }
        $gameOptions->setOptionName($_POST['gameMode']);
        $gameOptions->setGame($game);
        $gameOptionsRepository->save($gameOptions, true);

        //GameUser
        $gameUser=new GameUser();
        $user=$this->getUser();
        $gameUser->setUser($user);
        $gameUser->setScore(0);
        $gameUser->setGame($game);
        $gameUserRepository->save($gameUser, true);

        //Pokemon
        foreach ($pokemonList as $pokemon)
        {
            $game->addPokemon($pokemon);
        }
        $gameRepository->save($game, true);
        

        //Selon le nombre de cases on affecte des dimensions
        switch ($_POST['pairsNumber'] * 2)
        {
            case 4:
                $dimensions=['y'=>'2', 'x'=>'2'];
            break;
            
            case 6:
                $dimensions=['y'=>'2', 'x'=>'3'];
            break;
            
            case 8:
                $dimensions=['y'=>'2', 'x'=>'4'];
            break;
            
            case 10:
                $dimensions=['y'=>'2', 'x'=>'5'];
            break;
            
            case 12:
                $dimensions=['y'=>'3', 'x'=>'4'];
            break;
            
            case 14:
                $dimensions=['y'=>'2', 'x'=>'7'];
            break;
            
            case 16:
                $dimensions=['y'=>'4', 'x'=>'4'];
            break;
            
            default:
                /* Erreur */;
        }

        //Création de la grille
        $k=0; //Compteur pour $pokemonList
        //Lignes
        for ($i=0 ; $i<$dimensions['x'] ; $i++)
        {
            //Colonnes
            for ($j=0 ; $j<$dimensions['y'] ; $j++)
            {
                //Converti un pokemon en tableau et le stocke dans la grille
                $pokemonGrid[$i][$j]=$pokemonList[$k]->convertToArray();
                $k++;
            }
        }
        
        //Stockage de la grille en session
        $_SESSION['pokemonGrid']=$pokemonGrid;

        return $this->render('game/index.html.twig', [
            'controller_name' => 'GameController',
            'showCardsAtStart' => $_POST['showCardsAtStart'],
            'timer' => $_POST['timer'],
            'pairsNumber' => $_POST['pairsNumber'],
            'triesNumber' => $_POST['triesNumber'],
            'gameMode' => $_POST['gameMode'],
            'dimensions' => $dimensions,
        ]);
    }

    //Echange entre le frontend et le backend à chaque clic
    #[Route('/frontBackExchange', name: 'app_frontBackExchange')]
    public function frontBackExchange(Request $request, GameRepository $gameRepository, GameUserRepository $gameUserRepository)
    {
        $identicalBoxes=false; //Préciser si les cases sont identiques dans le retour au front end.
        $score=0;
        
        //Récupère le body (json) de la requete transformé en array
        $data = $request->toArray();

		//-------------- Changer les $_POST -----------------
		
		if ($data['clickNumber']==1)
		{
			//Stocker la position de la 1ere case cliquée
			$_SESSION['click1']=array(
				'x'=>$data['boxClickedPositionX'],
				'y'=>$data['boxClickedPositionY']
			);
		}
		else
		{
			if ($data['clickNumber']==2)
			{
				//Stocker la position de la 2eme case cliquée
				$_SESSION['click2']=array(
					'x'=>$data['boxClickedPositionX'],
					'y'=>$data['boxClickedPositionY']
				);
				
				//Selon le mode de jeu on va comparer des choses différentes pour savoir si les 2 cases cliquées sont identiques
                switch ($_SESSION['Difficulty']['gameMode'])
                {
                    //Compare l'api_id
                    case 'pokemonPairs':
                        $boxClicked1=$_SESSION['pokemonGrid'][$_SESSION['click1']['x']][$_SESSION['click1']['y']]['api_id'];
                        $boxClicked2=$_SESSION['pokemonGrid'][$_SESSION['click2']['x']][$_SESSION['click2']['y']]['api_id'];
                        break;
                    //Compare le type à l'aide d'un tableau typeList sauvegardé en SESSION
                    case 'typePairs':
                        //Cherche l'api_id dans le tableau typeList et récupère le nom du type associé
                        $index=array_search($_SESSION['pokemonGrid'][$_SESSION['click1']['x']][$_SESSION['click1']['y']]['api_id'], array_column ($_SESSION['typeList'], 'id'));
                        $boxClicked1=$_SESSION['typeList'][$index]['type'];
                        $index=array_search($_SESSION['pokemonGrid'][$_SESSION['click2']['x']][$_SESSION['click2']['y']]['api_id'], array_column ($_SESSION['typeList'], 'id'));
                        $boxClicked2=$_SESSION['typeList'][$index]['type'];
                        break;
                    //Compare la generation
                    case 'genPairs':
                        $boxClicked1=$_SESSION['pokemonGrid'][$_SESSION['click1']['x']][$_SESSION['click1']['y']]['generation'];
                        $boxClicked2=$_SESSION['pokemonGrid'][$_SESSION['click2']['x']][$_SESSION['click2']['y']]['generation'];
                        break;
                    default:
                }

                //Vérifier si les 2 cases cliquées sont identiques
				if ($boxClicked1 == $boxClicked2)
				{
					//identiques
					$identicalBoxes=true;
                    
                    //Si la partie est finie
                    if ($_SESSION['Difficulty']['pairsNumber'] == $data['pairsFoundNumber']+1)//pairsFoundNumber a un retard de 1 pour le backend
                    {
                        //Récupérer la Game courrante pour récupérer le GameUser pour y stocker le score
                        $game=$gameRepository->findOneBy(['id' => $_SESSION['gameId']]);
                        $gameUsers=$game->getGameUsers();
                        foreach ($gameUsers as $item)
                        {
                            $gameUser=$item;
                        }
                        
                        //Calcul du score selon la difficulté
                        //Montrer les cartes au départ
                        if ($_SESSION['Difficulty']['showCardsAtStart']=='showCardsAtStartNo')
                        {
                            $score+=1;
                        }
                        //Timer
                        if ($_SESSION['Difficulty']['timer']=='timerYes')
                        {
                            $score+=1;
                        }
                        //Nombre de paires
                        $score+=$_SESSION['Difficulty']['pairsNumber'];
                        //Nombre d'essais ----------------- triesNumber pas implémenté
                        if ($_SESSION['Difficulty']['triesNumber'] > $_SESSION['Difficulty']['pairsNumber'])
                        {
                            $score=$score - ($_SESSION['Difficulty']['triesNumber'] - $_SESSION['Difficulty']['pairsNumber']);
                        }
                        //Mode de jeu
                        switch($_SESSION['Difficulty']['gameMode'])
                        {
                            case 'pokemonPairs':
                                $score+=0;
                                break;
                            case 'typePairs':
                                $score+=2;
                                break;
                            case 'genPairs':
                                $score+=5;
                                break;
                            default:
                        }
                        
                        //Sauvegarder le score
                        $gameUser->setScore($score);
                        $gameUserRepository->save($gameUser, true);
                    }
				}
				else
				{
					//différentes
					$identicalBoxes=false;
				}
			}
		}
		
		//Envoi de l'adresse de l'image de la case cliquée        
        return $this->json(['image' => $_SESSION['pokemonGrid'][$data['boxClickedPositionX']][$data['boxClickedPositionY']]['image'], 'identicalBoxes' => $identicalBoxes, 'score' => $score]);
    }
}
