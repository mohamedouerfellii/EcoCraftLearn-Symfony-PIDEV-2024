<?php

namespace App\Controller;

use App\Entity\Collectspts;
use App\Entity\Collects;
use App\Repository\CollectsRepository;
use App\Form\CollectsptsType;
use App\Form\CollectsType;
use App\Repository\CollectsptsRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Snipe\BanBuilder\CensorWords;


class CollectsptsController extends AbstractController
{



    #[Route('/admin/indexx', name: 'app_indexx')]
    public function indexx(): Response
    {

        return $this->render('client/index.html.twig');
    }
   
    #[Route('/admin/add_collectspts', name: 'add_collectspts')]

    public function Add(Request  $request , ManagerRegistry $doctrine ,SluggerInterface $slugger, SessionInterface $session) : Response {

        $Collectspts =  new Collectspts() ;
        $form =  $this->createForm(CollectsptsType::class,$Collectspts) ;
        $form->add('Ajouter' , SubmitType::class) ;
        $form->handleRequest($request) ;


        
  
    
    


        if($form->isSubmitted()&& $form->isValid()){
            $brochureFile = $form->get('image')->getData();
            
            $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
            //$uploads_directory = $this->getParameter('upload_directory');
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();
            //$fileName = md5(uniqid()).'.'.$file->guessExtension();
            $brochureFile->move(
                $this->getParameter('upload_directory'),
                $newFilename
            );
            $Collectspts->setImage(($newFilename));


              
        


            
            $em= $doctrine->getManager() ;
            $em->persist($Collectspts);
            $em->flush();
            $collectsptsName = $Collectspts->getName();

        // Créer le message de notification
        $notificationMessage = "L'ajout a été effectué pour cet utilisateur, nom de la collectspts : $collectsptsName";

       

            return $this ->redirectToRoute('add_collectspts') ;
        }
        return $this->render('collectspts/backOffice/addcollectsptss.html.twig' , [
            'form' => $form->createView()
        ]) ;
    }

    #[Route('/student/afficher_collectspts', name: 'afficher_collectspts')]
   
public function AfficheCollectspts(CollectsptsRepository $repo, PaginatorInterface $paginator, Request $request): Response
{
    $collectsptss=$repo->findAll() ;

    $pagination = $paginator->paginate(
        $collectsptss,
        $request->query->getInt('page', 1),
        2// items per page
    );

    return $this->render('collectspts/fontOffice/index.html.twig', [
        'Collectspts' => $pagination,
        'ajoutA' => $collectsptss
    ]);
}
   #[Route('/admin/afficher_collectspts2', name: 'afficher_collectspts2')]
   
public function AfficheCollectspts2(CollectsptsRepository $repo, PaginatorInterface $paginator, Request $request): Response
{
    $collectsptss=$repo->findAll() ;

    $pagination = $paginator->paginate(
        $collectsptss,
        $request->query->getInt('page', 1),
        3 // items per page
    );

    return $this->render('collectspts/backOffice/index2.html.twig', [
        'Collectspts' => $pagination,
        'ajoutA' => $collectsptss
    ]);
}
    #[Route('/admin/delete_ab/{id}', name: 'delete_ab')]
    public function Delete($id,CollectsptsRepository $repository , ManagerRegistry $doctrine) : Response {
        $Collectspts=$repository->find($id) ;
        $em=$doctrine->getManager() ;
        $em->remove($Collectspts);
        $em->flush();
        return $this->redirectToRoute("afficher_collectspts") ;

    }
     #[Route('/admin/delete_av/{id}', name: 'delete_av')]
    public function Delete2($id,CollectsptsRepository $repository , ManagerRegistry $doctrine) : Response {
        $Collectspts=$repository->find($id) ;
        $em=$doctrine->getManager() ;
        $em->remove($Collectspts);
        $em->flush();
        return $this->redirectToRoute("afficher_collectspts2") ;

    }
    #[Route('/admin/update_ab/{id}', name: 'update_ab')]
    function update(CollectsptsRepository $repo,$id,Request $request , ManagerRegistry $doctrine,SluggerInterface $slugger){
        $Collectspts = $repo->find($id) ;
        $form=$this->createForm(CollectsptsType::class,$Collectspts) ;
        $form->add('update' , SubmitType::class) ;
        $form->handleRequest($request) ;
        if($form->isSubmitted()&& $form->isValid()){
            $brochureFile = $form->get('image')->getData();
            
            $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
            
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();
            
            $brochureFile->move(
                $this->getParameter('upload_directory'),
                $newFilename
            );
            $Collectspts->setImage(($newFilename));

            $Collectspts = $form->getData();
            $em=$doctrine->getManager() ;
            $em->flush();
            return $this ->redirectToRoute('afficher_collectspts2') ;
        }
        return $this->render('collectspts/backOffice/updatecollectsptss.html.twig' , [
            'form' => $form->createView()
        ]) ;

    }

    #[Route('/admin/update_av/{id}', name: 'update_av')]
    function update2(CollectsptsRepository $repo,$id,Request $request , ManagerRegistry $doctrine,SluggerInterface $slugger){
        $Collectspts = $repo->find($id) ;
        $form=$this->createForm(CollectsptsType::class,$Collectspts) ;
        $form->add('update' , SubmitType::class) ;
        $form->handleRequest($request) ;
        if($form->isSubmitted()&& $form->isValid()){
            $brochureFile = $form->get('image')->getData();
            
            $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
            
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();
            
            $brochureFile->move(
                $this->getParameter('upload_directory'),
                $newFilename
            );
            $Collectspts->setImage(($newFilename));

            $Collectspts = $form->getData();
            $em=$doctrine->getManager() ;
            $em->flush();
            return $this ->redirectToRoute('afficher_collectspts2') ;
        }
        return $this->render('collectspts/backOffice/updatecollectsptss.html.twig' , [
            'form' => $form->createView()
        ]) ;

    }


}
//******* *///******* *//


