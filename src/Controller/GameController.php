<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    #[Route('/game', name: 'app_game')]
    public function index(): Response
    {
        return $this->render('game/index.html.twig', [
            'controller_name' => 'GameController',
            'showCardsAtStart' => $_POST['showCardsAtStart'],
            'timer' => $_POST['timer'],
            'pairsNumber' => $_POST['pairsNumber'],
            'triesNumber' => $_POST['triesNumber'],
            'gameMode' => $_POST['gameMode'],
        ]);
        
    }
}
