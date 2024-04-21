<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Posts;
use App\Entity\Users;
use App\Form\CommentType;
use App\Form\PostsType;
use App\Form\UpdateFormType;
use App\Repository\PostsRepository;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationRequestHandler;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class PostsController extends AbstractController
{
    #[Route('/', name: 'forum_page')]
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
    
    #[Route('/back', name: 'back_office')]
    public function backOffice(ManagerRegistry $doctrine): Response
    {
        $posts = $doctrine->getManager()->getRepository(Posts::class)->findAll();
        $form = $this->createForm(PostsType::class); // Create the form here
        
        return $this->render('posts/backOffice/forumBack.html.twig', [
            'posts' => $posts,
            'form' => $form->createView() // Pass the form variable to the template
        ]);
    }


    #[Route('/addPost', name: 'addpost')]
    public function addPost(ManagerRegistry $doctrine, Request $request,SluggerInterface $slugger): Response
    {
        $user = $doctrine->getManager()->getRepository(Users::class)->find(8);
        $post = new Posts();
        $post->setOwner($user);
        
       
        $form = $this->createForm(PostsType::class, $post);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('attachment')->getData(); // Correct field name
            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();
                try {
                    $file->move(
                        $this->getParameter('posts_directory'), // Use the correct parameter name
                        $newFilename
                    );
                } catch (FileException $e) {
                    $e->getMessage();
                }
                $post->setAttachment($newFilename);
            }
            $post->setPosteddate(new \DateTime());
            $entityManager = $doctrine->getManager();
            $entityManager->persist($post);
            $entityManager->flush();
            
            return $this->redirectToRoute('forum_page');
           
        }
        
        $posts = $doctrine->getManager()->getRepository(Posts::class)->findAll(); // Fetch posts again
        return $this->render('posts/frontOffice/forumPage.html.twig', [
            'posts' => $posts,
            'form' => $form->createView() // Pass the form variable to the template
        ]);
       
    }   
    #[Route('/deletepostback/{id}', name: "deleteBack")]

    public function deleteBack($id, PostsRepository $repo, ManagerRegistry $m)
    {


        $em = $m->getManager();

        $post = $repo->find($id);


        $em->remove($post);

        $em->flush();

        return $this->redirectToRoute("back_office");
    }
    #[Route('/deletepostfront/{id}', name: "deleteFront")]
      public function deleteFront($id, PostsRepository $repo, ManagerRegistry $m)
    {


        $em = $m->getManager();

        $post = $repo->find($id);


        $em->remove($post);

        $em->flush();

        return $this->redirectToRoute("forum_page");
    }
    #[Route('/updateform/{id}', name: "update")]
public function updatestudent($id, PostsRepository $repo, ManagerRegistry $m, Request $req, SluggerInterface $slugger)
{
    $post = $repo->find($id);
   
    $em = $m->getManager();
    $post = $em->getRepository(Posts::class)->find($req->get('id'));
    $updateform = $this->createForm(UpdateFormType::class, $post);
    $updateform->handleRequest($req);

    if ($updateform->isSubmitted() && $updateform->isValid()) {
        $file = $updateform->get('attachment')->getData();
        if ($file) {
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();
            try {
                $file->move(
                    $this->getParameter('posts_directory'), // Use the correct parameter name
                    $newFilename
                );
                $post->setAttachment($newFilename);
            } catch (FileException $e) {
                // Handle file upload error
                $this->addFlash('error', 'File upload failed: ' . $e->getMessage());
                return $this->redirectToRoute('forum_page',['idCourse' => $post->getIdpost()]);
            }
        }

        // Persist and flush the changes
        $em->persist($post);
        $em->flush();

        // Redirect after successful form submission
        return $this->redirectToRoute("forum_page");
    }

    return $this->render("posts/frontOffice/updateForm.html.twig", ['updateform' => $updateform->createView()]);
}
}