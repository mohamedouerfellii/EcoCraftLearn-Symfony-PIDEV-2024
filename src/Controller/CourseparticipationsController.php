<?php

namespace App\Controller;

use App\Entity\Courseparticipations;
use App\Entity\Courses;
use App\Entity\NotificationNewCourse;
use App\Entity\Users;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class CourseparticipationsController extends AbstractController
{
    #[Route('/student/enrollCourse/{idCourse}', name: 'enroll_course')]
    public function enrollCourse(ManagerRegistry $doctrine, Request $request, HubInterface $hub,Security $security)
    {
        $idCourse = $request->get('idCourse');
        $idUser = 0;
        if($security->getUser() instanceof Users)
            $idUser = $security->getUser()->getIduser();
        // set up course participation
        $cp = new Courseparticipations();
        $em = $doctrine->getManager();
        $course = $em->getRepository(Courses::class)->find($idCourse);
        $participant = $security->getUser();
        $cp->setCourse($course);
        $cp->setParticipant($participant);
        $cp->setParticipationdate(new \DateTime());
        $course->setNbrregistred($course->getNbrregistred() + 1);
        // set up notification
        $notification = new NotificationNewCourse();
        $notification->setTitle("+1 Enrollment ". $course->getTitle());
        $notification->setIsChecked(false);
        $notification->setDate(new \DateTime());
        $notification->setTutorNotified($course->getTutor());
        $notification->setCourseNotified($course);
        $update = new Update(
            'https://ecocraftlearning/enroll/'.$course->getTutor()->getIduser(),
            json_encode([
                'status' => 'New Participation Offer!',
                'idTutor' => $course->getTutor()->getIduser()
                ])
            );
        $hub->publish($update);
        // persist changes to database
        $em->persist($notification);
        $em->persist($cp);
        $em->persist($course);
        $em->flush();

        return $this->redirectToRoute('my_learning',[
            'idUser' => $idUser
        ]);
    }

    #[Route('/student/myLearning/{idUser}', name: 'my_learning')]
    public function myLearning(ManagerRegistry $doctrine,Request $request,Security $security)
    {
        $idUser = 0;
        if($security->getUser() instanceof Users)
            $idUser = $security->getUser()->getIduser();
        $courses = $doctrine->getManager()->getRepository(Courseparticipations::class)->findByparticipant($idUser);
        return $this->render('courses/frontOffice/myLearning.html.twig', [
            'courses' => $courses
        ]);
    }

    #[Route('/student/unrollCourse/{idCourse}/{idUser}', name: 'unroll_course')]
    public function unrollCourse(ManagerRegistry $doctrine,Request $request,Security $security)
    {
        $em = $doctrine->getManager();
        $idUser = 0;
        if($security->getUser() instanceof Users)
            $idUser = $security->getUser()->getIduser();
        $idCourse = $request->get('idCourse');
        $cp = $em->getRepository(Courseparticipations::class)->findCpByUserCourse($idCourse,$idUser);
        $course = $cp->getCourse();
        $course->setNbrregistred($course->getNbrregistred() - 1);
        $em->remove($cp);
        $em->persist($course);
        $em->flush();
        return $this->redirectToRoute('my_learning',[
            'idUser' => 14
        ]);
    }
}
