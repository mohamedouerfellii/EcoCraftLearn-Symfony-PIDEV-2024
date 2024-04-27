<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Core\Security;
use App\Entity\Users;
use Symfony\Component\Filesystem\Filesystem;
use App\Form\EditUserType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Entity\Courses;

class UsersController extends AbstractController
{
    private $tokenStorage;
    public function __construct(
        private Security $security,TokenStorageInterface $tokenStorage
    ){
        $this->tokenStorage = $tokenStorage;
    }


    #[Route('/profil', name: 'profil_app')]
    public function profil(ManagerRegistry $doctrine,Request $request): Response
    {
        return $this->render('users/profil.html.twig', [
            'user' => $this->security->getUser()
        ]);
    }

    #[Route('/editprofil', name: 'edit_user')]
    public function editProfil(ManagerRegistry $doctrine,Request $request,SluggerInterface $slugger)
    {
        $em = $doctrine->getManager();
        $user=$this->security->getUser();
      
        $form = $this->createForm(EditUserType::class,$user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $userImage = $form->get('image')->getData();
            if ($userImage) {
                $originalFilename = pathinfo($userImage->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $userImage->guessExtension();
                try {
                    $userImage->move(
                        $this->getParameter('users_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $e->getMessage();
                }
                if ($user instanceof \App\Entity\Users) {
                    // Now you can access additional properties or methods of your user class
                    $user->setImage($newFilename);
                } 
                
            }
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('profil_app', ['user' => $user]);
            }
    return $this->render('users/editprofil.html.twig', [
        'form' => $form->createView()
    ]);
    }
    

    #[Route('/deleteProfil', name: 'delete_profil')]
    public function deleteProfil(ManagerRegistry $doctrine,Request $request,Filesystem $filesystem, TokenStorageInterface $tokenStorage){
        $em = $doctrine->getManager();
        $user=$this->security->getUser();
        if ($user instanceof \App\Entity\Users) {
            $userImage = $user->getImage();
        } 
       
        $em->remove($user);
        $em->flush();
        $tokenStorage->setToken(null);
        if ($userImage) {
            $usersDirectory = $this->getParameter('users_directory');
            $imagePath = $usersDirectory . '/' . $userImage;
            if ($filesystem->exists($imagePath)) {
                $filesystem->remove($imagePath);
            }
        }
        return $this->redirectToRoute('app_login');
    }

    #[Route('/admin/Dashboard', name: 'admin_dashboard')]
    public function allUsersDashboard(ManagerRegistry $doctrine): Response
    {
        $users = $doctrine->getManager()->getRepository(Users::class)->findAll();
        return $this->render('users/allUsers.html.twig', [
            'controller_name' => 'CoursesController',
            'users' => $users,
            'user' => $this->security->getUser()
        ]);
    }
    #[Route('/deleteProfil/{id}', name: 'delete_user')]
    public function deleteUser(ManagerRegistry $doctrine,Request $request,Filesystem $filesystem,  $id){
        $em = $doctrine->getManager();
        $user = $em->getRepository(Users::class)->find($id);
        $userImage = $user->getImage();
        $em->remove($user);
        $em->flush();
        if ($userImage) {
            $usersDirectory = $this->getParameter('users_directory');
            $imagePath = $usersDirectory . '/' . $userImage;
            if ($filesystem->exists($imagePath)) {
                $filesystem->remove($imagePath);
            }
        }
        return $this->redirectToRoute('admin_dashboard');
    }


    #[Route('/', name: 'visitor_app')]
    public function over(ManagerRegistry $doctrine,Request $request): Response
    {
        $courses = $doctrine->getManager()->getRepository(Courses::class)->showCoursesHomePage(14);
        return $this->render('users/index.html.twig', [
            'courses' => $courses
        ]);
    }

   
}