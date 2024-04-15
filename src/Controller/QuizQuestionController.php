<?php

namespace App\Controller;

use App\Entity\Courses;
use App\Entity\Quizquestions;
use App\Entity\Quizzes;
use App\Entity\Sections;
use App\Form\AddQuestionFormType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Length;

class QuizQuestionController extends AbstractController
{
    #[Route('/deleteQuestion/{idCourse}/{idQuestion}/{sectionIndex}', name: 'delete_question')]
    public function addQuestion(ManagerRegistry $doctrine, Request $request)
    {
        $em = $doctrine->getManager();
        $question = $em->getRepository(Quizquestions::class)->find($request->get('idQuestion'));
        $quiz = $question->getQuiz();
        $idQuiz = $quiz->getIdquiz();
        $em->remove($question);
        $em->flush();
        $nbrQuestion = $em->getRepository(Quizquestions::class)->questionsNbrByQuiz($idQuiz);
        if($nbrQuestion == 0){
            $em->remove($quiz);
            $em->flush();
        }
        return $this->redirectToRoute('show_section',[
            'idCourse' => $request->get('idCourse'),
            'sectionIndex' => $request->get('sectionIndex')
        ]); 
    }

}
