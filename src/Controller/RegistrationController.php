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
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bridge\Twig\Mime\BodyRenderer;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;





class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager,SluggerInterface $slugger, Security $security,KernelInterface $kernel): Response
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
                 $email = (new  TemplatedEmail())
                     ->from('mohamedouerfelli3@gmail.com') // Replace with your email address
                     ->to('jr.monam123@gmail.com') // Replace with recipient's email address
                     ->subject('Verification Account') // Replace with your desired subject
                     ->text('Welcom to EcoCraft learning ,We Hope you enjoy this Experience .' . $url)
                     ->htmlTemplate('users/mailTemplate.html.twig')
                     ->context([
                     'emailTitle' => 'Verify Account',
                     'emailDesc' => 'Verify Account.',
                     'url' => $url,
                     'btnTitle' => 'Active'
                     ]);

                     $templatesDir = $kernel->getProjectDir() . '/templates';
                     $loader = new FilesystemLoader($templatesDir);
                     $twigEnv = new Environment($loader);
                     $twigBodyRenderer = new BodyRenderer($twigEnv);
                     $twigBodyRenderer->render($email);
                 // Send the email
                 $mailer->send($email);
             
                $this->addFlash('message','a Verfification email sent , check your email');
             } catch (TransportExceptionInterface $e) {
                 // Handle transport errors and return an error response
                 $this->addFlash('danger','An error occurred while sending the emaill');
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
