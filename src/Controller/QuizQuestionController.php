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

class QuizQuestionController extends AbstractController
{
    /* #[Route('/addQuestion/{idCourse}/{idSection}/{sectionIndex}', name: 'add_question')]
    public function addQuestion(ManagerRegistry $doctrine, Request $request)
    {
        $em = $doctrine->getManager();
        $idCourse = $request->get('idCourse');
        $sectionIndex = $request->get('sectionIndex');
        $idSection = $request->get('idSection');
        $question = new Quizquestions();
        $form = $this->createForm(AddQuestionFormType::class, $question);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $quiz = $em->getRepository(Quizzes::class)->getQuizBySection($idSection);
            if($quiz == null){
                $quiz = new Quizzes();
                $lastId = $em->getRepository(Quizzes::class)->getLastId();
                $section = $em->getRepository(Sections::class)->find($idSection);
                if($lastId == null) $quiz->setIdquiz(1);
                else $quiz->setIdquiz($lastId + 1);
                $quiz->setSection($section);
                $em->persist($quiz);
            } 
            $question->setQuiz($quiz);
            $em->persist($question);
            $em->flush();
            return $this->redirectToRoute('show_section',[
                'idCourse' => $idCourse,
                'sectionIndex' => $sectionIndex
            ]); 
        }
        $course = $em->getRepository(Courses::class)->find($idCourse);
        $sections = $em->getRepository(Sections::class)->sectionListByCourse($idCourse);
        $quiz = $em->getRepository(Quizzes::class)->getQuizBySection($idSection);
        $quizquestions = null;
        if($quiz == null) {
            $quiz->setIdquiz(0);
            $quizquestions = $em->getRepository(Quizquestions::class)->getQuestionsByQuiz($quiz->getIdquiz());
        }
        return $this->render('sections/backOffice/afterEnroll.html.twig', [
            'course' => $course,
            'sections' => $sections,
            'sectionIndex' => $sectionIndex,
            'quizquestions' => $quizquestions,
            'QuestionForm' => $form->createView()
        ]);
    } */
}
