<?php

namespace App\Controller;

use App\Entity\Courseparticipations;
use App\Entity\Courses;
use App\Entity\Users;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CourseparticipationsController extends AbstractController
{
    #[Route('/enrollCourse/{idCourse}', name: 'enroll_course')]
    public function enrollCourse(ManagerRegistry $doctrine, Request $request)
    {
        $idCourse = $request->get('idCourse');
        $cp = new Courseparticipations();
        $em = $doctrine->getManager();
        $course = $em->getRepository(Courses::class)->find($idCourse);
        $cp->setCourse($course);
        $cp->setParticipant($em->getRepository(Users::class)->find(14));
        $cp->setParticipationdate(new \DateTime());
        $course->setNbrregistred($course->getNbrregistred() + 1);
        $em->persist($cp);
        $em->persist($course);
        $em->flush();
        return $this->redirectToRoute('my_learning',[
            'idUser' => 14
        ]);
    }
    #[Route('/myLearning/{idUser}', name: 'my_learning')]
    public function myLearning(ManagerRegistry $doctrine,Request $request)
    {
        $idUser = $request->get('idUser');
        $courses = $doctrine->getManager()->getRepository(Courseparticipations::class)->findByparticipant($idUser);
        return $this->render('courses/frontOffice/myLearning.html.twig', [
            'courses' => $courses
        ]);
    }
    #[Route('/unrollCourse/{idCourse}/{idUser}', name: 'unroll_course')]
    public function unrollCourse(ManagerRegistry $doctrine,Request $request)
    {
        $em = $doctrine->getManager();
        $idUser = $request->get('idUser');
        $idCourse = $request->get('idCourse');
        $cp = $em->getRepository(Courseparticipations::class)->findCpByUserCourse($idCourse,$idUser);
        $em->remove($cp);
        $em->flush();
        return $this->redirectToRoute('my_learning',[
            'idUser' => 14
        ]);
    }
}
