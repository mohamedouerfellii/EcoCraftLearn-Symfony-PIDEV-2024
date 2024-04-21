<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Posts;
use App\Entity\Users;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\CommentType;

class CommentsController extends AbstractController
{
    #[Route('/comments', name: 'forum')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(CommentType::class);
        return $this->render('comments/commentAddForm.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/addcomment/{id_post}', name: "add_comment")]
    public function addComment(ManagerRegistry $doctrine, Request $request, int $id_post): Response
    {
        $entityManager = $doctrine->getManager();
        $post = $entityManager->getRepository(Posts::class)->find($id_post);
        
        
        if (!$post) {
            // Handle the error appropriately if the post doesn't exist
            throw $this->createNotFoundException('No post found for id '.$id_post);
        }

        $comment = new Comments();
        $comment->setOwner($post->getOwner());
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setPost($post); // Link the comment to the found post
            $entityManager->persist($comment);
            $entityManager->flush();

            // Redirect to a route that displays the post, you might want to create one if it doesn't exist
            return $this->redirectToRoute('forum_page', ['id' => $id_post]);
        }

        // If not submitted or not valid, show the form again
        return $this->render('posts/frontOffice/forumPage.html.twig', [
            'posts' => [$post],  // Encapsulate the post in an array for consistency
            'comment_form' => $form->createView(),
        ]);
    }
}