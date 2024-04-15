<?php

namespace App\Controller;

use App\Entity\Quizanswers;
use App\Entity\Quizzes;
use App\Entity\Users;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class QuizAnswersController extends AbstractController
{
    #[Route('/submitAnswer/{idCourse}/{sectionIndex}/{idQuiz}/{result}', name: 'submit_answer')]
    public function submitAnswer(Request $request,ManagerRegistry $doctrine)
    {
        $em = $doctrine->getManager();
        $answer = new Quizanswers();
        $answer->setQuizz($em->getRepository(Quizzes::class)->find($request->get('idQuiz')));
        $answer->setResult($request->get('result'));
        $answer->setStudent($em->getRepository(Users::class)->find(14));
        $answer->setAnswerdate(new \DateTime());
        $em->persist($answer);
        $em->flush();
        return $this->redirectToRoute('after_enroll',[
            'idCourse' => $request->get('idCourse'),
            'sectionIndex' => $request->get('sectionIndex')
        ]);
    }
}
