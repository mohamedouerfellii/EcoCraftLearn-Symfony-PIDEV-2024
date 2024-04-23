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
use App\Form\EditCommentType;
use App\Form\PostsType;
use App\Repository\CommentsRepository;

class CommentsController extends AbstractController
{
    #[Route('/', name: 'forum')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $posts = $doctrine->getManager()->getRepository(Posts::class)->findAllPostsAndComments();
        $postForm = $this->createForm(PostsType::class); // Create the form here
        $commentForms = [];
    foreach ($posts as $post) {
        $comment = new Comments();
        $commentForm = $this->createForm(CommentType::class, $comment, [
            'action' => $this->generateUrl('add_comment', ['id_post' => $post->getIdpost()]),
            'method' => 'POST',
        ]);
        $commentForms[$post->getIdpost()] = $commentForm->createView();
    }

    // Pass all necessary data to the template
    return $this->render('posts/frontOffice/forumPage.html.twig', [
        'posts' => $posts,
        'form' => $postForm->createView(), // This is the form for creating a new post
        'commentForms' => $commentForms, // This is an array of forms for adding comments to each post
    ]);
    }

    #[Route('/addcomment/{id_post}', name: "add_comment")]
    public function addComment(ManagerRegistry $doctrine, Request $request, int $id_post): Response
    {
        $postForm = $this->createForm(PostsType::class);
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
            return $this->redirectToRoute('forum', ['id' => $id_post]);
        }

        // If not submitted or not valid, show the form again
        return $this->render('posts/frontOffice/forumPage.html.twig', [
            'posts' => $post,  // Encapsulate the post in an array for consistency
            'commentForms' => $form->createView(),
            'form'=>$postForm->createView()
        ]);
    }
    #[Route('/updateComment/{id}', name: "update_comment")]
    public function updatestudent($id, CommentsRepository $repo, ManagerRegistry $m, Request $req)
    {
        $comment = $repo->find($id);
       
        $em = $m->getManager();
        $comment = $em->getRepository(comments::class)->find($req->get('id'));
        $updatecomment = $this->createForm(EditCommentType::class, $comment);
        $updatecomment->handleRequest($req);
    
        if ($updatecomment->isSubmitted() && $updatecomment->isValid()) {
           
                
                    $em->persist($comment);
            $em->flush();
    
                
            
    
         
            return $this->redirectToRoute("forum");
        }
    
        return $this->render("comments/EditComment.html.twig", ['updatecomment' => $updatecomment->createView()]);
    }
    #[Route('/deleteComment{id}', name: "deletecomment")]

    public function deleteBack($id, CommentsRepository $repo, ManagerRegistry $m)
    {


        $em = $m->getManager();

        $comment = $repo->find($id);


        $em->remove($comment);

        $em->flush();

        return $this->redirectToRoute("forum");
    }
}