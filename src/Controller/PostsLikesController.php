<?php

namespace App\Controller;

use App\Entity\Posts;
use App\Entity\Postslikes;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
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
public function likePost(int $id_post, EntityManagerInterface $entityManager, Request $request): JsonResponse
{
    // Get the current user (assuming a static user)
    $user = $this->getStaticUser($entityManager);

    // Get the JSON content from the request body
    $data = json_decode($request->getContent(), true);

    // Get the action from the JSON data
    $action = $data['action'] ?? null;

    // Get the post from the database
    $post = $entityManager->getRepository(Posts::class)->find($id_post);
    
    // Check if the post exists
    if (!$post) {
        return new JsonResponse(['error' => 'Post not found'], 404);
    }

    // Check if the user has already liked the post
    $likedPost = $entityManager->getRepository(Postslikes::class)->findOneBy([
        'idUser' => $user->getIduser(), // Assuming user ID is stored in 'id' property
        'post' => $post->getIdPost(),
    ]);

    // Create or update the liked post entity
    if (!$likedPost) {
        $likedPost = new Postslikes();
        $likedPost->setUser($user); // Set the user
        $likedPost->setPost($post); // Set the post
    }

    // Set the action
    $likedPost->setAction($action);

    // Persist the changes to the database
    $entityManager->persist($likedPost);
    $entityManager->flush();

    // Return success response
    return new JsonResponse(['success' => true]);
}

// You need to implement this method to get the static user
private function getStaticUser(EntityManagerInterface $entityManager): Users
{
    // Replace this with your logic to get the static user
    // For example, if the static user ID is 1
    $user = $entityManager->getRepository(Users::class)->find(8);
    if (!$user) {
        // Handle error if user not found
    }
    return $user;
}

}