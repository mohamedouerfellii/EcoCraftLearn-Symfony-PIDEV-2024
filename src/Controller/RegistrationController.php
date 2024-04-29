<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager,SluggerInterface $slugger, Security $security): Response
    {
        if ($security->isGranted('IS_AUTHENTICATED_FULLY')) {
           
            if ($security->isGranted('ROLE_ADMIN')) {
                return $this->redirectToRoute('admin_dashboard');
            } elseif ($security->isGranted('ROLE_TEACHER')) {
                return $this->redirectToRoute('tutor_course_dashboard');
            } elseif ($security->isGranted('ROLE_STUDENT')) {
                return $this->redirectToRoute('home_page');
            }
        }
        $user = new Users();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            
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
    $user->setImage($newFilename);}

            $entityManager->persist($user);
            $entityManager->flush();
             // generate a signed url and email it to the user
             $mailer = new Mailer(Transport::fromDsn('smtp://mohamedouerfelli3@gmail.com:vbnlkplloybfhowc@smtp.gmail.com:587'));

             try {
                 // Create the email
                 $url= "http://127.0.0.1:8000/verify/email/".$user->getEmail();
                 $email = (new Email())
                     ->from('mohamedouerfelli3@gmail.com') // Replace with your email address
                     ->to('jr.monam123@gmail.com') // Replace with recipient's email address
                     ->subject('Test Email Subject') // Replace with your desired subject
                     ->text('This is a test email sent from Symfony.' . $url); // Replace with plain text content
             
                 // Send the email
                 $mailer->send($email);
             
                return new Response('a Verfification email sent , check your email', Response::HTTP_OK);
             } catch (TransportExceptionInterface $e) {
                 // Handle transport errors and return an error response
                 return new Response('An error occurred while sending the email: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
             }
 
             return $this->redirectToRoute('app_login');
         
        }

        return $this->render('registration/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    #[Route('/verify/email/{to}', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator,EntityManagerInterface $doctrine): Response
    {
        try {
            $user = $doctrine->getRepository(Users::class)->findOneBy(['email' => $request->get('to')]);
            $user->setIsVerified(true);
             $doctrine->persist($user);
           $doctrine->flush();          
        } catch (VerifyEmailExceptionInterface $exception) {
          

            return $this->redirectToRoute('app_register');
        }
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_login');
    }








}
