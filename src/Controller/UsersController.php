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
use App\Form\RegisterUserType;
use Doctrine\Persistence\ManagerRegistry;


class UsersController extends AbstractController
{
    public function __construct(
        private Security $security,
    ){
    }

    #[Route('/profil', name: 'profil_app')]
    public function profil(ManagerRegistry $doctrine,Request $request): Response
    {
        return $this->render('users/profil.html.twig', [
            'user' => $this->security->getUser()
        ]);
    }
    
   
}