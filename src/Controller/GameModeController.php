<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameModeController extends AbstractController
{
    #[Route('/game/mode', name: 'app_game_mode')]
    public function index(): Response
    {
        return $this->render('game_mode/index.html.twig', [
            'controller_name' => 'GameModeController',
        ]);
    }
}
