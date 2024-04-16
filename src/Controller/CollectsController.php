<?php

namespace App\Controller;

use App\Entity\Collects;
use App\Form\CollectsType;
use App\Repository\CollectsRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

use App\Repository\CategoriecodepromoRepository;
use App\Entity\Categoriecodepromo;

use App\Form\CategoriecodepromoType;



use Symfony\Component\String\Slugger\SluggerInterface;


use Snipe\BanBuilder\CensorWords;
use Dompdf\Dompdf;
use Dompdf\Options;
class CollectsController extends AbstractController
{
    #[Route('/collects', name: 'app_collects')]
    public function index(): Response
    {
        return $this->render('base.html.twig', [
            'controller_name' => 'CollectsController',
        ]);
    }
   #[Route('/add_collects', name: 'add_collects')]
public function AddCollects(
    Request $request,
    ManagerRegistry $doctrine,
    
    SessionInterface $session
): Response {
    $collects = new Collects();
    $form = $this->createForm(CollectsType::class, $collects);
    $form->add('Ajouter', SubmitType::class);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $collects = $form->getData();
        $em = $doctrine->getManager();

        
            $em->persist($collects);
            $em->flush();

            // Rediriger vers la page d'ajout de réservation
            return $this->redirectToRoute('add_collects');
        
    }

    return $this->render('collects/frontadd.html.twig', [
        'form' => $form->createView(),
        
    ]);
}

    #[Route('/afficher_collects', name: 'afficher_collects')]
    public function AfficheCollects (CollectsRepository $repo ,PaginatorInterface $paginator ,Request $request     ): Response
    {
        
        $Collects=$repo->findAll() ;
        $pagination = $paginator->paginate(
            $Collects,
            $request->query->getInt('page', 1),
            2 // items per page
        );
        return $this->render('collects/index.html.twig' , [
            'Collects' => $pagination ,
            'ajoutA' => $Collects
        ]) ;
    }

    #[Route('/delete_adh/{id}', name: 'delete_adh')]
    public function Delete($id,CollectsRepository $repository , ManagerRegistry $doctrine) : Response {
        $Collects=$repository->find($id) ;
        $em=$doctrine->getManager() ;
        $em->remove($Collects);
        $em->flush();
        return $this->redirectToRoute("afficher_collects") ;

    }
    #[Route('/update_adh/{id}', name: 'update_adh')]
    function update(CollectsRepository $repo,$id,Request $request , ManagerRegistry $doctrine){
        $Collects = $repo->find($id) ;
        $form=$this->createForm(CollectsType::class,$Collects) ;
        $form->add('update' , SubmitType::class) ;
        $form->handleRequest($request) ;
        if($form->isSubmitted()&& $form->isValid()){

            $Collects = $form->getData();
            $em=$doctrine->getManager() ;
            $em->flush();
            return $this ->redirectToRoute('afficher_collects') ;
        }
        return $this->render('collects/update.html.twig' , [
            'form' => $form->createView()
        ]) ;

    }
    



   
}
