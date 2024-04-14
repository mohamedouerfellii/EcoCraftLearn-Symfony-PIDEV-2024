<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

use App\Entity\Users;
use App\Form\RegisterUserType;

class UsersController extends AbstractController
{
    #[Route('/register', name: 'user_register')]
    public function register(Request $request,UserPasswordHasherInterface $passwordHasher,EntityManagerInterface $entityManager,SluggerInterface $slugger): Response
    {
        $user = new Users();
        $form = $this->createForm(RegisterUserType::class ,$user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
           
            $user->setIsactive(true);
            $user->setNbrptscollects(0); 
            $plainPassword = $form->get('password')->getData();
            $hashedPassword = hash('sha256', $plainPassword);
            $user->setPassword($hashedPassword);
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
                $user->setImage($newFilename);
            }
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('home_page');
        }

        return $this->render('users/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

   
}