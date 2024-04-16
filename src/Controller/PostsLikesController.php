<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostsLikesController extends AbstractController
{
    #[Route('/posts/likes', name: 'app_posts_likes')]
    public function index(): Response
    {
        return $this->render('posts_likes/index.html.twig', [
            'controller_name' => 'PostsLikesController',
        ]);
    }
}
