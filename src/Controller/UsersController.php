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
use App\Form\RegistrationFormType;
use App\Repository\UsersRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;



class UsersController extends AbstractController
{
    private $tokenStorage;
    public function __construct(
        private Security $security,TokenStorageInterface $tokenStorage
    ){
        $this->tokenStorage = $tokenStorage;
    }


    #[Route('/student/profil', name: 'profil_app')]
    public function profil(ManagerRegistry $doctrine,Request $request): Response
    {
        return $this->render('users/frontOffice/profil.html.twig', [
            'user' => $this->security->getUser()
        ]);
    }

    #[Route('/student/editprofil', name: 'edit_user')]
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
    return $this->render('users/frontOffice/editprofil.html.twig', [
        'form' => $form->createView()
    ]);
    }
    

    #[Route('/student/deleteProfil', name: 'delete_profil')]
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
        return $this->render('users/backOffice/allUsers.html.twig', [
            'controller_name' => 'UserssController',
            'users' => $users,
            'user' => $this->security->getUser()
        ]);
    }
    #[Route('/admin/blockProfil/{id}', name: 'block_user')]
    public function blockUser(ManagerRegistry $doctrine,Request $request,Filesystem $filesystem,  $id){
        $em = $doctrine->getManager();
        $user = $em->getRepository(Users::class)->find($id);
        $user->setIsactive(false);
        $em->persist($user);
        $em->flush();
        
        return $this->redirectToRoute('admin_dashboard');
    }
    #[Route('/admin/unblockProfil/{id}', name: 'unblock_user')]
    public function unblockUser(ManagerRegistry $doctrine,Request $request,Filesystem $filesystem,  $id){
        $em = $doctrine->getManager();
        $user = $em->getRepository(Users::class)->find($id);
        $user->setIsactive(true);
        $em->persist($user);
        $em->flush();
        
        return $this->redirectToRoute('admin_dashboard');
    }
    #[Route('/admin/makeAdmin/{id}', name: 'make_admin')]
    public function makeAdmin(ManagerRegistry $doctrine,Request $request,Filesystem $filesystem,  $id){
        $em = $doctrine->getManager();
        $user = $em->getRepository(Users::class)->find($id);
        $user->setRole("admin");
        $em->persist($user);
        $em->flush();
        
        return $this->redirectToRoute('admin_dashboard');
    }



    #[Route('/', name: 'visitor_app')]
    public function over(ManagerRegistry $doctrine,Request $request, Security $security): Response
    { 
      
           
            if ($security->isGranted('ROLE_ADMIN')) {
                return $this->redirectToRoute('admin_dashboard');
            } elseif ($security->isGranted('ROLE_TEACHER')) {
                return $this->redirectToRoute('tutor_course_dashboard');
            } elseif ($security->isGranted('ROLE_STUDENT')) {
                return $this->redirectToRoute('home_page');
            }
      


        $courses = $doctrine->getManager()->getRepository(Courses::class)->showCoursesHomePage(14);
        return $this->render('users/index.html.twig', [
            'courses' => $courses
        ]);
    }



    #[Route('/googleregister', name: 'google_user')]
    public function googleProfil(ManagerRegistry $doctrine,Request $request,UserPasswordHasherInterface $userPasswordHasher,SluggerInterface $slugger)
    {
        $em = $doctrine->getManager();
        $user = $this->security->getUser();
        if ($request->isMethod('POST')) {
            $password = $request->request->get('password');
            $role = $request->request->get('role');
            $gender = $request->request->get('gender');
            $phoneNumber = $request->request->get('numtel');
    
            if ($user instanceof \App\Entity\Users) {

                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $password
                    )
                );
                $user->setRole($role);
                $user->setGender($gender);
                $user->setNumtel($phoneNumber);
             
    
                // Persist user
                $em->persist($user);
                $em->flush();
                if ($role === 'teacher') {
                    return $this->redirectToRoute('tutor_course_dashboard');
                } elseif ($role === 'student') {
                    return $this->redirectToRoute('home_page');
                }
            }
        }
    
        return $this->render('users/googleProfil.html.twig');
}

#[Route('/student/editpassword', name: 'update_student_password')]
public function editstudentpassword(ManagerRegistry $doctrine, Request $request, UserPasswordHasherInterface $userPasswordHasher)
{
    $em = $doctrine->getManager();
    $user = $this->security->getUser();
    $errorMessages = [];

    if ($request->isMethod('POST')) {
        $currentPassword = $request->request->get('password');
        $newPassword = $request->request->get('new');
        $confirmedPassword = $request->request->get('conf');

        // Check if the current password matches the user's actual password
        if ($user instanceof \App\Entity\Users && $userPasswordHasher->isPasswordValid($user, $currentPassword)) {
            // Check if the new password and confirmed password match
            if ($newPassword === $confirmedPassword) {
                // Hash and set the new password
                $hashedPassword = $userPasswordHasher->hashPassword($user, $newPassword);
                $user->setPassword($hashedPassword);

                $em->persist($user);
                $em->flush();
                return $this->redirectToRoute('profil_app');
            } else {
                $errorMessages[] = "New password and confirmed password do not match";
            }
        } else {
            $errorMessages[] = "Current password is incorrect";
        }
    }

    return $this->render('users/frontOffice/editpassword.html.twig', [
        'user' => $user,
        'errorMessages' => $errorMessages,
    ]);
}



#[Route('/admin/searchUsersBack', name: 'search_User_back')]
public function searchUsersBack(UsersRepository $usersRepository, Request $request): JsonResponse
{
    $search = $request->query->get('search');
    $users = $usersRepository->searchUsersBack($search);

    if (empty($users)) {
        return new JsonResponse([], JsonResponse::HTTP_OK);
    }

    $dataToJson = $this->serializeUsers($users);

    return new JsonResponse($dataToJson, JsonResponse::HTTP_OK, [], true);
}

private function serializeUsers(array $users): string
{
    $serializer = $this->get('serializer');
    return $serializer->serialize($users, 'json');
}






}