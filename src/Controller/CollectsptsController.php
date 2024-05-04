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



    #[Route('/indexx', name: 'app_indexx')]
    public function indexx(): Response
    {

        return $this->render('client/index.html.twig');
    }
    #[Route('/collectspts', name: 'app_collectspts')]
    public function index(): Response
    {
        return $this->render('admin.html.twig', [
            'controller_name' => 'CollectsptsController',
        ]);
    }
    #[Route('/add_collectspts', name: 'add_collectspts')]

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
        return $this->render('collectspts/addcollectsptss.html.twig' , [
            'form' => $form->createView()
        ]) ;
    }

    #[Route('/afficher_collectspts', name: 'afficher_collectspts')]
   
public function AfficheCollectspts(CollectsptsRepository $repo, PaginatorInterface $paginator, Request $request): Response
{
    $collectsptss=$repo->findAll() ;

    $pagination = $paginator->paginate(
        $collectsptss,
        $request->query->getInt('page', 1),
        2// items per page
    );

    return $this->render('collectspts/index.html.twig', [
        'Collectspts' => $pagination,
        'ajoutA' => $collectsptss
    ]);
}
   #[Route('/afficher_collectspts2', name: 'afficher_collectspts2')]
   
public function AfficheCollectspts2(CollectsptsRepository $repo, PaginatorInterface $paginator, Request $request): Response
{
    $collectsptss=$repo->findAll() ;

    $pagination = $paginator->paginate(
        $collectsptss,
        $request->query->getInt('page', 1),
        3 // items per page
    );

    return $this->render('collectspts/index2.html.twig', [
        'Collectspts' => $pagination,
        'ajoutA' => $collectsptss
    ]);
}
    #[Route('/delete_ab/{id}', name: 'delete_ab')]
    public function Delete($id,CollectsptsRepository $repository , ManagerRegistry $doctrine) : Response {
        $Collectspts=$repository->find($id) ;
        $em=$doctrine->getManager() ;
        $em->remove($Collectspts);
        $em->flush();
        return $this->redirectToRoute("afficher_collectspts") ;

    }
     #[Route('/delete_av/{id}', name: 'delete_av')]
    public function Delete2($id,CollectsptsRepository $repository , ManagerRegistry $doctrine) : Response {
        $Collectspts=$repository->find($id) ;
        $em=$doctrine->getManager() ;
        $em->remove($Collectspts);
        $em->flush();
        return $this->redirectToRoute("afficher_collectspts2") ;

    }
    #[Route('/update_ab/{id}', name: 'update_ab')]
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
            return $this ->redirectToRoute('afficher_collectspts') ;
        }
        return $this->render('collectspts/updatecollectsptss.html.twig' , [
            'form' => $form->createView()
        ]) ;

    }

    #[Route('/update_av/{id}', name: 'update_av')]
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
        return $this->render('collectspts/updatecollectsptss.html.twig' , [
            'form' => $form->createView()
        ]) ;

    }

 
    #[Route('/add_coll', name: 'add_coll')]
    
public function sendMessage(Request $request, ManagerRegistry $doctrine, SessionInterface $session): Response
{
    // Vérifiez si la variable 'collects_done' existe dans la session
    if (!$session->has('collects_done')) {
        // Si elle n'existe pas, initialisez-la à 0
        $session->set('collects_done', 0);
    }

    $collects = new Collects();
    $form = $this->createForm(CollectsType::class, $collects);
    $form->add('Ajouter', SubmitType::class);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $collects = $form->getData();
        $em = $doctrine->getManager();
        $em->persist($collects);
        $em->flush();

        // Mettez à jour la variable dans la session à 1 lorsque la réservation est effectuée
        $session->set('collects_done', 1);

        return $this->redirectToRoute('add_coll');
    }

    return $this->render('collects/frontcollecter.html.twig', [
        'form' => $form->createView()
    ]);
}

 
 

  

}
//******* *///******* */


