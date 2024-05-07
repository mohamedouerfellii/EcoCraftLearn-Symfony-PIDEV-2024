<?php

namespace App\Controller;

use App\Entity\Posts;
use App\Entity\Postslikes;
use App\Entity\Users;
use App\Repository\PostsLikesRepository;
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
    #[Route('/addlike/{id_post}', name: 'like_post', methods: ['POST'])]
    public function likePost(int $id_post, EntityManagerInterface $entityManager, Request $request): JsonResponse
    {

        $user = $this->getUser();
        $data = json_decode($request->getContent(), true);
        $action = $data['action'] ?? null;
        $post = $entityManager->getRepository(Posts::class)->find($id_post);

        if (!$post) {
            return new JsonResponse(['error' => 'Post not found'], 404);
        }


        $likedPost = $entityManager->getRepository(Postslikes::class)->findOneBy([
            'idUser' => $user->getIduser(),
            'post' => $post->getIdPost(),
        ]);


        if (!$likedPost) {
            $likedPost = new Postslikes();
            $likedPost->setUser($user);
            $likedPost->setPost($post);
        }


        $likedPost->setAction($action);


        $entityManager->persist($likedPost);
        $entityManager->flush();


        return new JsonResponse(['success' => true]);
    }

    #[Route('/likes/{likedUserId}', name: 'app_posts_likes')]
    public function isLiked(int $likedUserId, PostsLikesRepository $likeRepository): Response
    {
        $currentUserId = $this->getUser();
        $post = $likeRepository->find($likedUserId);


        $isLiked = $likeRepository->likeExist($post, $currentUserId);


        return $this->render('posts/frontOffice/forumPage.html.twig', [
            'isLiked' => $isLiked !== null,
        ]);
    }
}