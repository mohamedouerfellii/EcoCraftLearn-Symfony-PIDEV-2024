<?php

namespace App\Controller;
use App\Entity\Carts;
use App\Entity\Products;
use App\Entity\Souscarts;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

use App\Form\QuantityFormType;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartsController extends AbstractController
{
    #[Route('/carts', name: 'app_carts')]
    public function index(): Response
    {
        return $this->render('carts/index.html.twig', [
            'controller_name' => 'CartsController',
        ]);
    }

    #[Route('/buy-now/{idproduct}', name: 'buy_now')]
    public function buyNow($idproduct, EntityManagerInterface $entityManager, Request $request): Response
    {
        $product = $entityManager->getRepository(Products::class)->find($idproduct);
    
        if (!$product) {
            return $this->redirectToRoute('error');
        }
    
        $user = $entityManager->getRepository(Users::class)->find(8);
        if (!$user) {
            return $this->redirectToRoute('error');
        }
    
        $form = $this->createForm(QuantityFormType::class);
    
 
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
          
            $quantity = $form->get('quantity')->getData();
    
           
            $totalPrice = $product->getPrice() * $quantity;

            $cart = $entityManager->getRepository(Carts::class)->findOneBy([
                'owner' => $user,
                'isconfirmed' => 0
            ]);
            
            if (!$cart) {
                $cart = new Carts();
                $cart->setOwner($user);
                $cart->setIsconfirmed(0);
                $entityManager->persist($cart);
            }
            $subCart = new Souscarts();
            $subCart->setProduct($product);
            $subCart->setQuantiteproduct($quantity);
            $subCart->setCart($cart);
            $entityManager->persist($subCart);
            
            $cart->setTotalprice($cart->getTotalprice() + $totalPrice);
            $entityManager->flush();
            return $this->redirectToRoute('showProducts');
    
        }
        return $this->render('products/frontOffice/AddQuantityProduct.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    

    
}
