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
use App\Repository\CoursesRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use InfoBip;
use Knp\Component\Pager\PaginatorInterface;
use LocationInfoService;
use Symfony\Bridge\Twig\Mime\BodyRenderer;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Notifier\TexterInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class CoursesController extends AbstractController
{
    #[Route('/', name: 'home_page')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $courses = $doctrine->getManager()->getRepository(Courses::class)->showCoursesHomePage(14);
        return $this->render('courses/frontOffice/homePage.html.twig', [
            'courses' => $courses
        ]);
    }
    #[Route('/tutorCoursesDashboard', name: 'tutor_course_dashboard')]
    public function tutorCoursesDashboard(ManagerRegistry $doctrine): Response
    {
        $idTutor = 8;
        $courses = $doctrine->getManager()->getRepository(Courses::class)->findBytutor($idTutor);
        return $this->render('courses/backOffice/coursesDashboardTutor.html.twig', [
            'controller_name' => 'CoursesController',
            'courses' => $courses
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
    #[Route('/deleteCourse/{idCourse}', name: 'delete_course')]
    public function deleteCourse(ManagerRegistry $doctrine,Request $request,Filesystem $filesystem, TexterInterface $texter, SessionInterface $session){
        $em = $doctrine->getManager();
        $idCourse = $request->get('idCourse');
        $course = $em->getRepository(Courses::class)->find($idCourse);
        $courseImage = $course->getImage();
        $password = $request->request->get('password');
        $idUser = $course->getTutor()->getIduser();
        $nbrTry = $nbrTry = $session->get('nbrTry', 0);
        $isPwdCorrect = $em->getRepository(Users::class)->doesPasswordMatch($idUser,$password);
        if($isPwdCorrect == null){
            $nbrTry = $nbrTry + 1;
            $session->set('nbrTry', $nbrTry);
            if($nbrTry >= 5){
                $locationInfo = LocationInfoService::getLocationInfo();
                $message = "Unauthorized Access Attempt,\nWe have detected an attempt to access your account :\nLocation : "
                .$locationInfo['regionName'].", ".$locationInfo['city'].
                ", ".$locationInfo['country'].
                ".\nPlease verify your account and change your password if necessary.";
                $infobip = new InfoBip($texter);
                $infobip->sendAlertSMS('21696726127',$message);
                return $this->redirectToRoute('tutor_course_dashboard');
            }
            return $this->redirectToRoute('tutor_course_details',[
                'idCourse' => $idCourse
            ]);
        }
        $session->set('nbrTry', 0);
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
    // filter course back
    #[Route('/filterCourseBack', name: 'filter_course_back')]
    public function filterCourseBack(CoursesRepository $coursesRep,Request $request): Response
    {
        $filter = $request->get('filter');
        $idTutor = 8;
        $courses = $coursesRep->filterCoursesBack($idTutor, $filter);
        $dataToJson = json_encode($courses);
        return new Response($dataToJson);
    }
    // search course back
    #[Route('/searchCourseBack', name: 'search_course_back')]
    public function searchCourseBack(CoursesRepository $coursesRep,Request $request): Response
    {
        $search = $request->get('search');
        $idTutor = 8;
        $courses = $coursesRep->searchCoursesBack($idTutor, $search);
        $dataToJson = json_encode($courses);
        return new Response($dataToJson);
    }
    // filter course front
    #[Route('/filterCourseFront', name: 'filter_course_front')]
    public function filterCourseFront(CoursesRepository $coursesRep,Request $request): Response
    {
        $filter = $request->get('filter');
        $idUser = 14;
        $courses = $coursesRep->filterCoursesFront($idUser, $filter);
        $dataToJson = json_encode($courses);
        return new Response($dataToJson);
    }
    // search course front
    #[Route('/searchCourseFront', name: 'search_course_front')]
    public function searchCourseFront(CoursesRepository $coursesRep,Request $request): Response
    {
        $filter = $request->get('search');
        $idUser = 14;
        $courses = $coursesRep->searchCoursesFront($idUser, $filter);
        $dataToJson = json_encode($courses);
        return new Response($dataToJson);
    }
    // start white board tutor
    #[Route('/whiteBoardTutor/{idCourse}', name: 'white_board_tutor')]
    public function startWBMeetingTutor(KernelInterface $kernel,ManagerRegistry $doctrine, Request $request): Response
    {
        $em = $doctrine->getManager(); 
        $idCourse = $request->get('idCourse');
        $url = "http://127.0.0.1:8000/whiteBoardStudent/" .$idCourse; 
        $mailer = new Mailer(Transport::fromDsn('smtp://mohamedouerfelli3@gmail.com:vbnlkplloybfhowc@smtp.gmail.com:587'));
        $participants = $em->getRepository(Courseparticipations::class)->getParticipantsMail($idCourse); 
        $email = ( new TemplatedEmail())
            ->from('mohamedouerfelli3@gmail.com')
            ->to(...$participants)
            ->subject('Join WhiteBoard Meeting')
            ->text('WhiteBoard Meeting. Click the following link to join: ' . $url)
            ->htmlTemplate('courses/mailTemplate.html.twig')
            ->context([
            'emailTitle' => 'Join WhiteBoard Meeting',
            'emailDesc' => 'WhiteBoard Meeting.',
            'url' => $url,
            'btnTitle' => 'Join Meeting'
            ]);
        $templatesDir = $kernel->getProjectDir() . '/templates';
        $loader = new FilesystemLoader($templatesDir);
        $twigEnv = new Environment($loader);
        $twigBodyRenderer = new BodyRenderer($twigEnv);
        $twigBodyRenderer->render($email);
        $mailer->send($email);
        $course = $em->getRepository(Courses::class)->find($idCourse);
        return $this->render('courses/backOffice/whiteBoard.html.twig', [
            'course' => $course
        ]);
    }
    // start white board student
    #[Route('/whiteBoardStudent/{idCourse}', name: 'white_board_student')]
    public function startWBMeetingStudent(CoursesRepository $coursesRep, Request $request, HubInterface $hub): Response
    {   
        $course = $coursesRep->find($request->get('idCourse'));
        $update = new Update(   // notify tutor that student is joined
            'https://ecocraftlearning/wbmeeting/'.$course->getIdcourse(),
            json_encode([
                'isConnected' => true,
                'studentDetail' => "Ouerfelli Mohamed",
                'idStudent' => 14
                ])
            );
        $hub->publish($update);
        $roomId = $course->getIdcourse() + $course->getTutor()->getIduser();
        return $this->render('courses/frontOffice/whiteBoard.html.twig', [
            'course' => $course,
            'roomId' => $roomId
        ]);
    }
}
