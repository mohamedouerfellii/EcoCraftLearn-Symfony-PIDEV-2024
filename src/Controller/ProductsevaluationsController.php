<?php

namespace App\Controller;
use App\Entity\Products;
use App\Entity\Users;
use App\Entity\Productsevaluations;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ProductsevaluationsFormType;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;




use App\Repository\ProductsevaluationsRepository;

use Symfony\Component\String\Slugger\SluggerInterface;
class ProductsevaluationsController extends AbstractController
{


    #[Route('/addevaluationproduct/{idproduct}', name: 'addevaluationproduct')]
    public function addevaluationproduct($idproduct, ManagerRegistry $doctrine, Request $request,ProductsevaluationsRepository $repository,): Response
    {  
        $product = $this->getDoctrine()->getRepository(Products::class)->find($idproduct);
        $user = $this->getDoctrine()->getRepository(Users::class)->find(10);
        $evaluation = new Productsevaluations();
        $evaluation->setProduct($product); 
        $evaluation->setEvaluator($user); 
        $form = $this->createForm(ProductsevaluationsFormType::class, $evaluation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($evaluation);
            $entityManager->flush();   
        }
        $evaluations = $repository->findByProduct($product);
        return $this->render('products/frontOffice/DetailProductHomePage.html.twig', [
            'form' => $form->createView(),
            'product' => $product,
            'evaluations' => $evaluations,
        ]);
    }


    #[Route('/showEvaluationsDashboard', name: 'showEvaluationsDashboard')]
    public function showAllEvaluationsDashboard(ProductsevaluationsRepository $rep,ManagerRegistry $doctrine,): Response
    {
        $evaluation = $doctrine->getManager()->getRepository(Productsevaluations::class)->findAll();
        return $this->render("products/backOffice/evaluationProductDashboardAdmin.html.twig", ["Evaluation" => $evaluation]);
    }

   

    

}
