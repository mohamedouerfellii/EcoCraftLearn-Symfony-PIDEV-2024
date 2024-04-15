<?php

namespace App\Controller;
use App\Entity\Carts;
use App\Entity\Commandes;
use App\Entity\Products;
use App\Entity\Souscarts;
use App\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\OrderFormType;
use App\Repository\CartsRepository;
use App\Repository\SouscartsRepository;
use App\Repository\CommandesRepository;
use App\Repository\ProductsRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
class OrdersController extends AbstractController
{
    #[Route('/order-product/{ownerId}/{idcarts}/{totalPrice}', name: 'OrderProduct')]
    public function confirmOrder(Request $request, int $ownerId, int $idcarts, float $totalPrice, ProductsRepository $productsRepository): Response
    {
        $user = $this->getDoctrine()->getRepository(Users::class)->find($ownerId);
        if (!$user) {
            return $this->redirectToRoute('error');
        }
        $cart = $this->getDoctrine()->getRepository(Carts::class)->find($idcarts);
    
        if (!$cart) {
            return $this->redirectToRoute('error');
        }
    
        $cart->setIsConfirmed(1);   
        $commande = new Commandes();
        $commande->setCart($cart); 
        $commande->setOwner($user); 
        $commande->setTotal($totalPrice);
        
    
        

    
        $form = $this->createForm(OrderFormType::class, $commande);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commande);
            $entityManager->flush();
            $productsRepository->decrementProductQuantity($idcarts);
            return $this->redirectToRoute('showProducts');
        }
    
        return $this->render('products/frontOffice/AddOrderProduct.html.twig', [
            'form' => $form->createView(),
        ]);






    }


    
    #[Route('/my-order', name: 'My_Order')]
    public function myOrder(CommandesRepository $rep):Response
    {
        $user = $this->getDoctrine()->getRepository(Users::class)->find(10);
        $commandes = $rep->findByOwner($user);
        
        if (!$commandes) {
            $this->addFlash('error', 'Your Orders not found.');
            return $this->redirectToRoute('showProducts');
        }

        return $this->render("products/frontOffice/OrdersProducPage.html.twig", ["Commandes" => $commandes]);
    
    }

    #[Route('/ordersinprogress', name: 'ordersinprogress')]
    public function ordersInProgress(CommandesRepository $commandesRepository): Response
    {
        $orders = $commandesRepository->findByStatus('inProgress');

        return $this->render("products/backOffice/OrderDashboardAdmin.html.twig", ["Orders" => $orders]);
    }

    #[Route('/DeleviredOrder/{idcommande}', name: 'DeleviredOrder')]
    public function markAsDelivered($idcommande, CommandesRepository $commandesRepository, EntityManagerInterface $entityManager): RedirectResponse
    {
        
        $commande = $commandesRepository->find($idcommande);
        if (!$commande) {
            throw $this->createNotFoundException('La commande n\'existe pas.');
        }
        $commande->setStatus('Delivered');
        $entityManager->flush();
        return $this->redirectToRoute('ordersinprogress');
    }
   


}
