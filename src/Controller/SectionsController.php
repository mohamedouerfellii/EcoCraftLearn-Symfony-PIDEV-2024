<?php

namespace App\Controller;

use App\Entity\Courses;
use App\Entity\Sections;
use App\Form\AddSectionFormType;
use App\Form\EditSectionFormType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class SectionsController extends AbstractController
{
    #[Route('/addSection{idCourse}', name: 'add_section')]
    public function addSection(ManagerRegistry $doctrine,Request $request,SluggerInterface $slugger)
    {
        $section = new Sections();
        $form = $this->createForm(AddSectionFormType::class, $section);
        $idCourse = $request->get('idCourse');
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $doctrine->getManager();
            $course = $em->getRepository(Courses::class)->find($idCourse);
            $course->setNbrsection($course->getNbrsection() + 1);
            $section->setCourse($course);
            $sectionAttachment = $form->get('attachment')->getData();
            if ($sectionAttachment) {
                $originalFilename = pathinfo($sectionAttachment->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $sectionAttachment->guessExtension();
                try {
                    $sectionAttachment->move(
                        $this->getParameter('courses_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $e->getMessage();
                }
                $section->setAttachment($newFilename);
            }
            $section->setPosteddate(new \DateTime());
            $em->persist($course);
            $em->persist($section);
            $em->flush();
            return $this->redirectToRoute('tutor_course_details',[
                'idCourse' => $idCourse
            ]);
        }
        return $this->render('sections/backOffice/addSection.html.twig',[
            'addSectionForm' => $form->createView(),
            'idCourse' => $idCourse
        ]);
    }
    #[Route('/sectionDetails/{idCourse}/{sectionIndex}', name: 'show_section')]
    public function courseDetails(ManagerRegistry $doctrine,Request $request)
    {
        $idCourse = $request->get('idCourse');
        $sectionIndex = $request->get('sectionIndex');
        $course = $doctrine->getManager()->getRepository(Courses::class)->find($idCourse);
        $sections = $doctrine->getManager()->getRepository(Sections::class)->sectionListByCourse($idCourse);
        return $this->render('sections/backOffice/afterEnroll.html.twig', [
            'course' => $course,
            'sections' => $sections,
            'sectionIndex' => $sectionIndex
        ]);
    }
    #[Route('/deleteSection{idSection}', name: 'delete_section')]
    public function deleteSection(ManagerRegistry $doctrine,Request $request,Filesystem $filesystem){
        $em = $doctrine->getManager();
        $section = $em->getRepository(Sections::class)->find($request->get('idSection'));
        $course = $section->getCourse();
        $course->setNbrsection($course->getNbrsection() - 1);
        $sectionAttachment = $section->getAttachment();
        $em->remove($section);
        $em->persist($course);
        $em->flush();
        if ($sectionAttachment) {
            $coursesDirectory = $this->getParameter('courses_directory');
            $imagePath = $coursesDirectory . '/' . $sectionAttachment;
            if ($filesystem->exists($imagePath)) {
                $filesystem->remove($imagePath);
            }
        }
        $sections = $em->getRepository(Sections::class)->sectionListByCourse($course->getIdcourse());
        if(empty($sections)) return $this->redirectToRoute('tutor_course_details',[
            'idCourse' => $course->getIdcourse()
        ]);
        return $this->redirectToRoute('show_section',[
            'idCourse' => $course->getIdcourse(),
            'sectionIndex' => 0
        ]); 
    }
    #[Route('/editSection/{idSection}/{sectionIndex}', name: 'edit_section')]
    public function editBook(ManagerRegistry $doctrine,Request $request,SluggerInterface $slugger)
    {
        $em = $doctrine->getManager();
        $idSection = $request->get('idSection');
        $sectionIndex = $request->get('sectionIndex');
        $section = $em->getRepository(Sections::class)->find($idSection);
        $idCourse = $section->getCourse()->getIdcourse();
        $form = $this->createForm(EditSectionFormType::class,$section);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $sectionAttachment = $form->get('attachment')->getData();
            if ($sectionAttachment) {
                $originalFilename = pathinfo($sectionAttachment->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $sectionAttachment->guessExtension();
                try {
                    $sectionAttachment->move(
                        $this->getParameter('courses_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $e->getMessage();
                }
                $section->setAttachment($newFilename);
            }
            $em->persist($section);
            $em->flush();
            return $this->redirectToRoute('show_section', [
                'idCourse' => $idCourse,
                'sectionIndex' => $sectionIndex
            ]);
            }
    return $this->render('sections/backOffice/editSection.html.twig', [
        'editForm' => $form->createView(),
        'idCourse' => $idCourse,
        'sectionIndex' => $sectionIndex
    ]);
    }
}
