<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Posts;
use App\Entity\Users;
use App\Form\CommentType;
use App\Form\PostsType;
use App\Form\SearchType;
use App\Form\UpdateFormType;
use App\Repository\PostsLikesRepository;
use App\Repository\PostsRepository;
use App\Service\SearchData;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use SMSGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Entity\Postslikes;


class PostsController extends AbstractController
{
    #[Route('/formPage', name: 'forum_page')]
    public function index(ManagerRegistry $doctrine, PostsLikesRepository $likeRepository): Response
    {
        $currentUserId = $this->getUser();
        $likes = $doctrine->getManager()->getRepository(PostsLikes::class)->likeByUser($currentUserId);
        // $post = $likeRepository->find($likedUserId);
        // $isLiked = $likeRepository->likeExist($post, $currentUserId);
        //   if ($isLiked == null) $isLiked = false;
        // else $isLiked = true;
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
            'likes' => $likes

        ]);
    }
    #[Route('/thatPost/{postId}', name: 'thatPost')]
    public function showPost($postId, EntityManagerInterface $entityManager): Response
    {
        $post = $entityManager->getRepository(Posts::class)->find($postId);

        if (!$post) {
            throw $this->createNotFoundException('Post not found');
        }

        return $this->render('posts/frontOffice/share.html.twig', [
            'post' => $post,
        ]);
    }

    #[Route('/back', name: 'back_office')]
    public function backOffice(ManagerRegistry $doctrine, Request $request, PostsRepository $postsRepository): Response
    {
        $posts = $doctrine->getManager()->getRepository(Posts::class)->findAll();
        $searchData = new SearchData();
        $form = $this->createForm(SearchType::class, $searchData);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $searchData->page = $request->query->getInt('page', 1);
            $posts = $postsRepository->findBySearch($searchData);
        }

        return $this->render('posts/backOffice/forumBack.html.twig', [
            'posts' => $posts,
            'form' => $form->createView()
        ]);
    }


    #[Route('/addPost', name: 'addpost')]
    public function addPost(ManagerRegistry $doctrine, Request $request, SluggerInterface $slugger, NotifierInterface $notifier): Response
    {



        $user = $this->getUser();
        $post = new Posts();
        $post->setOwner($user);


        $form = $this->createForm(PostsType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('attachment')->getData();
            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();
                try {
                    $file->move(
                        $this->getParameter('posts_directory'),
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
            $sms = new SMSGenerator();
            $sms->sendAlertSMS('21656330810', "A Post has been added in EcoCraftLearning!!!!");
            return $this->redirectToRoute('forum_page');
        }
        $posts = $doctrine->getManager()->getRepository(Posts::class)->findAll();
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
            'form' => $form->createView(),
            'commentForms' => $commentForms
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
                        $this->getParameter('posts_directory'),
                        $newFilename
                    );
                    $post->setAttachment($newFilename);
                } catch (FileException $e) {

                    $this->addFlash('error', 'File upload failed: ' . $e->getMessage());
                    return $this->redirectToRoute('forum_page', ['idCourse' => $post->getIdpost()]);
                }
            }


            $em->persist($post);
            $em->flush();


            return $this->redirectToRoute("forum_page");
        }

        return $this->render("posts/frontOffice/updateForm.html.twig", ['updateform' => $updateform->createView()]);
    }
    #[Route('/searchpost', name: 'search_Posts')]

    public function searchProduct(PostsRepository $productsRep, Request $request): Response
    {


        $search = $request->query->get('search');
        $posts = $productsRep->findByName($search);

        $dataToJson = $this->serializePosts($posts);

        return new Response($dataToJson, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    private function serializePosts(array $Posts): string
    {
        $serializer = $this->get('serializer');
        return $serializer->serialize($Posts, 'json');
    }
}
