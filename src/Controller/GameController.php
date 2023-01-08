<?php

namespace App\Controller;

use App\Repository\PokemonRepository;
use App\Repository\TypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    #[Route('/game', name: 'app_game')]
    public function index(PokemonRepository $pokemonRepository, TypeRepository $typeRepository): Response
    {
        switch ($_POST['gameMode'])
        {
            case 'pokemonPairs':
                //Récupère tous les pokemon
                $pokemonList=$pokemonRepository->findAll();
                //Randomise le tableau
                shuffle($pokemonList);
                //On garde seulement le nombre de pairs choisi
                array_splice($pokemonList, $_POST['pairsNumber'], count($pokemonList));
            break;
            
            case 'typePairs':
                $pokemonList=[]; //Initialisation du tableau pour l'utiliser dans le foreach
                //Récupère tous les types
                $typeList=$typeRepository->findAll();
                //Randomise le tableau
                shuffle($typeList);
                //On garde seulement le nombre de pairs choisi
                array_splice($typeList, $_POST['pairsNumber'], count($typeList));
                
                //Récupération des pokemon pour chaque type
                foreach ($typeList as $type)
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
                    }
                }
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
            break;
            
            default:
                /* Erreur */;
        }


        return $this->render('game/index.html.twig', [
            'controller_name' => 'GameController',
            'showCardsAtStart' => $_POST['showCardsAtStart'],
            'timer' => $_POST['timer'],
            'pairsNumber' => $_POST['pairsNumber'],
            'triesNumber' => $_POST['triesNumber'],
            'gameMode' => $_POST['gameMode'],
            'pokemonList' => $pokemonList,
        ]);
        
    }
}
