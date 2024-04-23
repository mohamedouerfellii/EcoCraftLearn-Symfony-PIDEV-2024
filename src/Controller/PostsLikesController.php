<?php

namespace App\Controller;

use App\Entity\Posts;
use App\Entity\Postslikes;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
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
   #[Route('/like/{id_post}', name: 'like_post', methods: ['POST'])]
public function likePost(int $id_post, EntityManagerInterface $entityManager): JsonResponse
{
    // Get the post from the database
    $post = $entityManager->getRepository(Posts::class)->find($id_post);
    
    // Check if the post exists
    if (!$post) {
        return new JsonResponse(['error' => 'Post not found'], 404);
    }

    // Get the current user (assuming a static user)
    $user = $entityManager->getRepository(Users::class)->find(1); // Static user ID

    // Check if the user has already liked the post
    $like = $entityManager->getRepository(PostsLikes::class)->findOneBy([
        'user' => $user,
        'post' => $post,
    ]);

    // Toggle the action between 0 and 1
    $action = $like ? !$like->getAction() : 1;

    // Create or update the like entity
    if (!$like) {
        $like = new PostsLikes();
        $like->setUser($user);
        $like->setPost($post); // Set the post object
        $like->setAction($action);
        $entityManager->persist($like);
    } else {
        $like->setAction($action);
    }

    // Persist the changes to the database
    $entityManager->flush();

    // Return the action as JSON response
    return new JsonResponse(['action' => $action]);
}

}