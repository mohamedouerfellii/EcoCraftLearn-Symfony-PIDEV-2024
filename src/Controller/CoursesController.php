<?php

namespace App\Controller;

use App\Entity\Courseparticipations;
use App\Entity\Courses;
use App\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\AddCourseFormType;
use App\Form\EditCourseFormType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;

class CoursesController extends AbstractController
{
    private $tokenStorage;
    public function __construct(
        private Security $security,TokenStorageInterface $tokenStorage
    ){
        $this->tokenStorage = $tokenStorage;
    }
    #[Route('/user/home', name: 'home_page')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $courses = $doctrine->getManager()->getRepository(Courses::class)->showCoursesHomePage(14);
        return $this->render('courses/frontOffice/homePage.html.twig', [
            'courses' => $courses
        ]);
    }
    #[Route('/admin/home', name: 'tutor_course_dashboard')]
    public function tutorCoursesDashboard(ManagerRegistry $doctrine): Response
    {
        $idTutor = 8;
        $courses = $doctrine->getManager()->getRepository(Courses::class)->findBytutor($idTutor);
        return $this->render('courses/backOffice/coursesDashboardTutor.html.twig', [
            'controller_name' => 'CoursesController',
            'courses' => $courses,
            'user' => $this->security->getUser()
        ]);
    }
    #[Route('/addNewCourse', name: 'add_new_course')]
    public function addNewCourse(ManagerRegistry $doctrine,Request $request,SluggerInterface $slugger)
    {
        $course = new Courses();
        $form = $this->createForm(AddCourseFormType::class, $course);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $doctrine->getManager();
            $course->setTutor($em->getRepository(Users::class)->find(8));
            $course->setNbrsection(0);
            $course->setNbrpersonrated(0);
            $course->setNbrregistred(0);
            $course->setRate(0);
            $courseImage = $form->get('image')->getData();
            if ($courseImage) {
                $originalFilename = pathinfo($courseImage->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $courseImage->guessExtension();
                try {
                    $courseImage->move(
                        $this->getParameter('courses_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $e->getMessage();
                }
                $course->setImage($newFilename);
            }
            $course->setPosteddate(new \DateTime());
            $em->persist($course);
            $em->flush();
            return $this->redirectToRoute('tutor_course_dashboard');
        }
        return $this->render('courses/backOffice/addNewCourse.html.twig',[
            'addCourseForm' => $form->createView()
        ]);
    }
    #[Route('/editCourse{idCourse}', name: 'edit_course')]
    public function editBook(ManagerRegistry $doctrine,Request $request,SluggerInterface $slugger)
    {
        $em = $doctrine->getManager();
        $course = $em->getRepository(Courses::class)->find($request->get('idCourse'));
        $form = $this->createForm(EditCourseFormType::class,$course);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $courseImage = $form->get('image')->getData();
            if ($courseImage) {
                $originalFilename = pathinfo($courseImage->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $courseImage->guessExtension();
                try {
                    $courseImage->move(
                        $this->getParameter('courses_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $e->getMessage();
                }
                $course->setImage($newFilename);
            }
            $em->persist($course);
            $em->flush();
            return $this->redirectToRoute('tutor_course_details', ['idCourse' => $course->getIdcourse()]);
            }
    return $this->render('courses/backOffice/editCourse.html.twig', [
        'editForm' => $form->createView()
    ]);
    }
    #[Route('/deleteCourse{idCourse}', name: 'delete_course')]
    public function deleteCourse(ManagerRegistry $doctrine,Request $request,Filesystem $filesystem){
        $em = $doctrine->getManager();
        $course = $em->getRepository(Courses::class)->find($request->get('idCourse'));
        $courseImage = $course->getImage();
        $em->remove($course);
        $em->flush();
        if ($courseImage) {
            $coursesDirectory = $this->getParameter('courses_directory');
            $imagePath = $coursesDirectory . '/' . $courseImage;
            if ($filesystem->exists($imagePath)) {
                $filesystem->remove($imagePath);
            }
        }
        return $this->redirectToRoute('tutor_course_dashboard');
    }
    #[Route('/courses/{page}', name: 'all_courses')]
    public function allCourses(ManagerRegistry $doctrine, PaginatorInterface $paginator,Request $request): Response
    {
        $page = $request->get('page',1);
        $pagination =  $paginator->paginate(
            $doctrine->getManager()->getRepository(Courses::class)->coursePagination(14),
            $page,
            6
        );
        return $this->render('courses/frontOffice/allCourses.html.twig', [
            'pagination' => $pagination
        ]);
    }
    #[Route('/courseDetails{idCourse}', name: 'course_details')]
    public function courseDetails(ManagerRegistry $doctrine,Request $request): Response
    {
        $course = $doctrine->getManager()->getRepository(Courses::class)->find($request->get('idCourse'));
        return $this->render('courses/frontOffice/courseDetails.html.twig', [
            'course' => $course
        ]);
    }
    #[Route('/tutorCourseDetails{idCourse}', name: 'tutor_course_details')]
    public function courseDetailsDashboard(ManagerRegistry $doctrine,Request $request): Response
    {
        $course = $doctrine->getManager()->getRepository(Courses::class)->find($request->get('idCourse'));
        return $this->render('courses/backOffice/courseDetails.html.twig', [
            'course' => $course
        ]);
    }
}
