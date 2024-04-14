<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuizzesController extends AbstractController
{
    #[Route('/quizzes', name: 'app_quizzes')]
    public function index(): Response
    {
        return $this->render('quizzes/index.html.twig', [
            'controller_name' => 'QuizzesController',
        ]);
    }
}
