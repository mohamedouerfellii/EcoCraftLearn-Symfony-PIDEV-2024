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
Request $request,ManagerRegistry $doctrine,SessionInterface $session): Response {
    $collects = new Collects();
    $form = $this->createForm(CollectsType::class, $collects);
    $form->add('Ajouter', SubmitType::class);
    $form->handleRequest($request);
     $censor = new CensorWords;
    $langs = array('fr', 'it', 'en-us', 'en-uk', 'es');
    $badwords = $censor->setDictionary($langs);
    $censor->setReplaceChar("*");


    if ($form->isSubmitted() && $form->isValid()) {
        $collects = $form->getData();
        $em = $doctrine->getManager();

         $string = $censor->censorString($collects->getMaterialType());
        $collects->setMaterialType($string['clean']);

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
        $searchTerm = $request->query->get('search');
    $Collects = $repo->searchByTypeOrNameOrComment($searchTerm);
        
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
    
    
#[Route('/update_collects/{id}', name: 'update_collects')]
public function update(CollectsRepository $repo, $id, Request $request, ManagerRegistry $doctrine): Response
{

    $collects = $repo->find($id);

    // Vérifier si la collecte avec l'ID spécifié existe
    if (!$collects) {
        throw $this->createNotFoundException('Collecte non trouvée');
    }

    $form = $this->createForm(CollectsType::class, $collects);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em = $doctrine->getManager();
        $em->flush();
        return $this->redirectToRoute('afficher_collects');
    }

    return $this->render('collects/update.html.twig', [
        'form' => $form->createView()
    ]);
}

#[Route('/generate_pdf', name: 'generate_pdf')]
public function generatePdfAction(CollectsRepository $repo, PaginatorInterface $paginator, Request $request): Response
{
    // Récupérer les réclamations avec pagination
    $searchTerm = $request->query->get('search');
    $reclamations = $repo->findAll();
    $pagination = $paginator->paginate(
        $reclamations,
        $request->query->getInt('page', 1),
        4 // items per page
    );

    // Rendre le contenu HTML à partir du modèle Twig
    $html = $this->renderView('collects/pdf_reclamations.html.twig', [
        'Collects' => $reclamations,
    ]);

    // Créer une instance de Dompdf avec les options de configuration
    $options = new Options();
    $options->set('isPhpEnabled', true); // Activer l'exécution de PHP dans le contenu HTML
    $dompdf = new Dompdf($options);

    // Charger le contenu HTML dans Dompdf
    $dompdf->loadHtml($html);

    // (Optionnel) Définir les options de rendu de Dompdf, par exemple la taille du papier, l'orientation, etc.

    // Rendre le PDF
    $dompdf->render();

    // Renvoyer la réponse avec le PDF en tant que contenu
    return new Response(
        $dompdf->output(),
        Response::HTTP_OK,
        [
            'Content-Type' => 'application/pdf',
        ]
    );
}







#[Route('/searchCollects', name: 'search_Collect_Front')]
public function searchCollects(CollectsRepository $collectsRepository, Request $request): Response
{

   
    $search = $request->query->get('search');
    $Collects = $collectsRepository->searchCollectByQuantity ($search);

    $dataToJson = $this->serializeCollects($Collects);
    
    return new Response($dataToJson, Response::HTTP_OK, ['Content-Type' => 'application/json']);
}

private function serializeCollects(array $Collects): string
{
    $serializer = $this->get('serializer');
    return $serializer->serialize($Collects, 'json');
}




   
}
