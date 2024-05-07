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
    #[Route('/', name: 'forum_page')]
    public function index(ManagerRegistry $doctrine): Response
    {
           

        $posts = $doctrine->getManager()->getRepository(Posts::class)->findAllPostsAndComments();
        $postForm = $this->createForm(PostsType::class);
        $commentForms = [];
    foreach ($posts as $post) {
        $comment = new Comments();
        $commentForm = $this->createForm(CommentType::class, $comment, [
            'action' => $this->generateUrl('add_comment', ['id_post' => $post->getIdpost()]),
            'method' => 'POST',
        ]);
        $commentForms[$post->getIdpost()] = $commentForm->createView();
    }

    
    return $this->render('posts/frontOffice/forumPage.html.twig', [
        'posts' => $posts,
        'form' => $postForm->createView(),
        'commentForms' => $commentForms,
    ]);
    
    }

    #[Route('/addcomment/{id_post}', name: "add_comment")]
    public function addComment(ManagerRegistry $doctrine, Request $request, int $id_post): Response
    {
        $entityManager = $doctrine->getManager();
    $post = $entityManager->getRepository(Posts::class)->find($id_post);

    if (!$post) {
        throw $this->createNotFoundException('Post not found');
    }
$user=$this->getUser();
    $comment = new Comments();
    $comment->setOwner($user);
    $comment->setPost($post);

    $form = $this->createForm(CommentType::class, $comment);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($comment);
        $entityManager->flush();

       
        return $this->redirectToRoute('forum_page');
    }

    
    $posts = $entityManager->getRepository(Posts::class)->findAllPostsAndComments();
    $postForm = $this->createForm(PostsType::class);
    $commentForms = [];
    foreach ($posts as $post) {
        $commentForm = $this->createForm(CommentType::class, new Comments(), [
            'action' => $this->generateUrl('add_comment', ['id_post' => $post->getIdpost()]),
            'method' => 'POST',
        ]);
        $commentForms[$post->getIdpost()] = $commentForm->createView();
    }

    return $this->render('posts/frontOffice/forumPage.html.twig', [
        'posts' => $posts,
        'form' => $postForm->createView(),
        'commentForms' => $commentForms,
        'formErrors' => $form->getErrors(true, false),
    ]);
    }
    #[Route('/updateComment/{id}', name: "update_comment")]
    public function updatestudent($id, CommentsRepository $repo, ManagerRegistry $m, Request $req)
    {
        $comment = $repo->find($id);
        $em = $m->getManager();
        $comment = $em->getRepository(Comments::class)->find($req->get('id'));
   
    $updateCommentForm = $this->createForm(EditCommentType::class, $comment);
    $updateCommentForm->handleRequest($req);

    if ($updateCommentForm->isSubmitted() && $updateCommentForm->isValid()) {
        $em->persist($comment);
        $em->flush();

        return $this->redirectToRoute("forum_page");
    }

    return $this->render("comments/EditComment.html.twig", [
        'updatecommentForm' => $updateCommentForm->createView()
    ]);
    }
    #[Route('/deleteComment{id}', name: "deletecomment")]

    public function deleteBack($id, CommentsRepository $repo, ManagerRegistry $m)
    {


        $em = $m->getManager();

        $comment = $repo->find($id);


        $em->remove($comment);

        $em->flush();

        return $this->redirectToRoute("forum_page");
    }
}