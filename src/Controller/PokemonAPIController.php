<?php

namespace App\Controller;

use App\Entity\Pokemon;
use App\Entity\Type;
use App\Repository\PokemonRepository;
use App\Repository\TypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PokemonAPIController extends AbstractController
{
    //Prérequis pour la requete vers l'API
    private HttpClientInterface $client;
    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    //Interface admin pour lancer la récupération de l'API
    #[Route('/pokemon-api', name: 'app_pokemon_api')]
    public function index(): Response
    {
        return $this->render('pokemon_api/index.html.twig', [
            'controller_name' => 'PokemonAPIController',
        ]);
    }

    //Récupération des pokemon et des types de l'API et enregistrement en BDD
    #[Route('/pokemon-api-get-data', name: 'app_pokemon_api_get_data')]
    public function getAPIData(TypeRepository $typeRepository, PokemonRepository $pokemonRepository): Response
    {
        try {
            //Envoi de la requete à l'API
            $response = $this->client->request(
                'GET',
                'https://pokebuildapi.fr/api/v1/pokemon'
            );
            //Pour des tests : https://pokebuildapi.fr/api/v1/pokemon/limit/20

            $statusCode = $response->getStatusCode(); //Récupérer le code de status
            $contentType = $response->getHeaders()['content-type'][0]; //Récupérer le type de contenu de la réponse

            //Si la requete s'est bien passée
            if ($statusCode === 200 && $contentType === 'application/json')
            {
                //Transforme les données en tableau
                $content = $response->toArray();

                //Traitement de chaque pokemon
                foreach ($content as $apiPokemon)
                {
                    //Si le pokemon courrant n'existe pas dans la BDD
                    $pokemon = $pokemonRepository->findOneBy(['api_id' => $apiPokemon['pokedexId']]);
                    if (!$pokemon)
                    {
                        //Création du pokemon
                        $pokemon = new Pokemon();
                        $pokemon->setApiId($apiPokemon['pokedexId'])
                            ->setName($apiPokemon['name'])
                            ->setImage($apiPokemon['image'])
                            ->setGeneration($apiPokemon['apiGeneration']);
                    }

                    //Traitement de chaque type du pokemon
                    foreach ($apiPokemon['apiTypes'] as $apiType)
                    {
                        //Si le type courrant du pokemon courrant n'existe pas dans la BDD
                        $type = $typeRepository->findOneBy(['type' => $apiType['name']]);
                        if (!$type)
                        {
                            //Création du type
                            $type = new Type();
                            $type->setType($apiType['name']);
                            //Enregistrement du type en BDD
                            $typeRepository->save($type, true);
                        }

                        //Ajout du type au pokemon créé s'il n'est pas déjà ajouté au pokemon
                        $pokemon->addType($type);
                    }

                    //Enregistrement du pokemon en BDD s'il n'existe pas déjà
                    $pokemonRepository->save($pokemon, true);
                }
            }
        } catch (ClientException $e) {
            dd($e);
        }

        return $this->redirectToRoute('app_home');
    }
}
