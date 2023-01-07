<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DifficultyController extends AbstractController
{
    #[Route('/difficulty', name: 'app_difficulty')]
    public function index(): Response
    {
        //On récupère le gameMode choisi et on le fait passer après vers Game
        return $this->render('difficulty/index.html.twig', [
            'controller_name' => 'DifficultyController',
            'gameMode' => $_POST['gameMode'],
        ]);
    }
}
