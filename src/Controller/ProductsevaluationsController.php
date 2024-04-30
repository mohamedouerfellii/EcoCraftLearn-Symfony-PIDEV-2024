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
    public function addevaluationproduct($idproduct, ManagerRegistry $doctrine, Request $request, ProductsevaluationsRepository $repository): Response
    {     


        $em = $doctrine->getManager();
        $product = $em->getRepository(Products::class)->find($request->get('idproduct'));
    
        $user = $this->getDoctrine()->getRepository(Users::class)->find(10);
          

        $countrate = $repository->countRatingsByProductId($idproduct);
        $sumrate = $repository->sumRatingByProductId($idproduct);
        $moyRate = $countrate > 0 ? $sumrate / $countrate : 0;


        $product = $this->getDoctrine()->getRepository(Products::class)->find($idproduct);
    
        $evaluation = new Productsevaluations();
        $evaluation->setProduct($product); 
        $evaluation->setEvaluator($user); 
        $form = $this->createForm(ProductsevaluationsFormType::class, $evaluation);
        $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();

          
                $evaluator = $repository->checkIfUserIsEvaluatorForProduct($user, $product);

       
                if ($evaluator) {
                    $this->addFlash('error', 'User is already an evaluator');
                    return $this->redirectToRoute('addevaluationproduct', ['idproduct' => $product->getIdproduct()]);
                }

            
                $entityManager->persist($evaluation);
                $entityManager->flush();
            }

        
        $evaluations = $repository->findByProduct($product);
        
        return $this->render('products/frontOffice/DetailProductHomePage.html.twig', [
            'form' => $form->createView(),
            'product' => $product,
            'evaluations' => $evaluations,
            'countrate' => $countrate,
            'moyRate' => $moyRate,
            'user' => $user,
        ]);   


    }
    

    

    #[Route('/showEvaluationsDashboard', name: 'showEvaluationsDashboard')]
    public function showAllEvaluationsDashboard(ProductsevaluationsRepository $rep,ManagerRegistry $doctrine,): Response
    {
        $evaluation = $doctrine->getManager()->getRepository(Productsevaluations::class)->findAll();
        return $this->render("products/backOffice/evaluationProductDashboardAdmin.html.twig", ["Evaluation" => $evaluation]);
    }

    #[Route('/deleteevaluation/{idevaluation}/{idproduct}', name: "deleteevaluation")]
    public function deleteevaluation(int $idevaluation , int $idproduct , ProductsevaluationsRepository $productsevaluationsRepository): Response
    {
        $evaluation = $this->getDoctrine()->getRepository(Productsevaluations::class)->find($idevaluation);
        if (!$evaluation) {
            throw $this->createNotFoundException('Entity not found');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($evaluation);
        $em->flush();

        return $this->redirectToRoute('addevaluationproduct', ['idproduct' => $idproduct]);

    }

   

    

}
