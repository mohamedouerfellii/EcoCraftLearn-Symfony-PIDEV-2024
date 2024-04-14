<?php

namespace App\Controller;

use App\Entity\Quizquestions;
use App\Entity\Quizzes;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class QuizzesController extends AbstractController
{
    #[Route('/passQuiz/{idSection}/{sectionIndex}', name: 'pass_quiz')]
    public function passQuiz(ManagerRegistry $doctrine,Request $request)
    {
        $idSection = $request->get('idSection');
        $sectionIndex = $request->get('sectionIndex');
        $em = $doctrine->getManager();
        $quiz = $em->getRepository(Quizzes::class)->getQuizBySection($idSection);
        $questions = $em->getRepository(Quizquestions::class)->getQuestionsByQuiz($quiz->getIdquiz());
        return $this->render('quizzes/frontOffice/passQuiz.html.twig',[
            'quiz' => $quiz,
            'questions' => $questions,
            'sectionIndex' => $sectionIndex
        ]);
    }
}
