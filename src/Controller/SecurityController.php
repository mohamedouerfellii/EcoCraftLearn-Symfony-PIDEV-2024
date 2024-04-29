<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Security;
use App\Form\ForgotPasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Users;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bridge\Twig\Mime\BodyRenderer;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;



class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, Security $security): Response
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


        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }






    #[Route(path: '/forgot', name: 'app_forgot')]
    public function forgotPassword(Request $request, ManagerRegistry $doctrine,MailerInterface $mailer, TokenGeneratorInterface  $tokenGenerator,KernelInterface $kernel)
    {


        $form = $this->createForm(ForgotPasswordType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $donnees = $form->getData('email');//
            $user = $doctrine->getManager()->getRepository(Users::class)->findOneBy(['email'=>$donnees]);
            if(!$user) {
                $this->addFlash('danger','cette adresse n\'existe pas');
                return $this->redirectToRoute("app_forgot");

            }
            $token = $tokenGenerator->generateToken();

            try{
                $user->setResetToken($token);
                $em = $doctrine->getManager();
                $em->persist($user);
                $em->flush();




            }catch(\Exception $exception) {
                $this->addFlash('warning','une erreur est survenue :'.$exception->getMessage());
                return $this->redirectToRoute("app_login");


            }
            $mailer = new Mailer(Transport::fromDsn('smtp://mohamedouerfelli3@gmail.com:vbnlkplloybfhowc@smtp.gmail.com:587'));
            $url = $this->generateUrl('app_reset_password',array('token'=>$token),UrlGeneratorInterface::ABSOLUTE_URL);
            $email = (new TemplatedEmail())
            ->from('mohamedouerfelli3@gmail.com') // Replace with your email address
            ->to($user->getEmail()) // Replace with recipient's email address
            ->subject('Forget Password') // Replace with your desired subject
            ->htmlTemplate('users/mailTemplate.html.twig')
                     ->context([
                     'emailTitle' => 'Rest Password',
                     'emailDesc' => 'Reset password.',
                     'url' => $url,
                     'btnTitle' => 'reset password'
                     ]);

                     $templatesDir = $kernel->getProjectDir() . '/templates';
                     $loader = new FilesystemLoader($templatesDir);
                     $twigEnv = new Environment($loader);
                     $twigBodyRenderer = new BodyRenderer($twigEnv);
                     $twigBodyRenderer->render($email);
        // Send the email
        $mailer->send($email);
        $this->addFlash('message','E-mail  de réinitialisation du mp envoyé :');



        }

        return $this->render("security/forgotPassword.html.twig",['form'=>$form->createView()]);
    }


  
    #[Route(path: '/resetpassword/{token}', name: 'app_reset_password')]
    public function resetpassword(Request $request, UserPasswordHasherInterface $userPasswordHasher,ManagerRegistry $doctrine)
    {
    
      

        if($request->isMethod('POST')) {
            $user = $doctrine->getManager()->getRepository(Users::class)->findOneBy(['reset_token'=>$request->get('token')]);

            if($user == null ) {
                $this->addFlash('danger','TOKEN INCONNU');
                return $this->redirectToRoute("app_login");
    
            }
            $password = $request->get('password');
            $confirmPassword = $request->get('confirmPassword');
        
            // Vérifiez si les deux champs de mot de passe sont identiques
            if ($password !== $confirmPassword) {
                $this->addFlash('danger', 'Passwords do not match!');
                return $this->redirectToRoute("app_reset_password", ['token' => $request->get('token')]);
            }
            
            $user->setResetToken(null);
            $user->setPassword($userPasswordHasher->hashPassword($user,$request->get('password')));
            $entityManger = $doctrine->getManager();
            $entityManger->persist($user);
            $entityManger->flush();

            $this->addFlash('message','Mot de passe mis à jour :');
            return $this->redirectToRoute("app_login");

        }
     
            return $this->render("security/resetPassword.html.twig",['token'=>$request->get('token')]);

 
    }






}
