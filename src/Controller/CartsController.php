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
use App\Repository\CartsRepository;
use App\Repository\SouscartsRepository;
class CartsController extends AbstractController
{
  

    #[Route('/buy-now/{idproduct}', name: 'buy_now')]
    public function buyNow($idproduct, EntityManagerInterface $entityManager, Request $request): Response
    {


        $user = $entityManager->getRepository(Users::class)->find(10);

        $product = $entityManager->getRepository(Products::class)->find($idproduct);

     $quantite = $product->getQuantite();

if ($quantite === 0) {
    $this->addFlash('error', 'Solde Out Of this Product');
    return $this->redirectToRoute('showProducts');
}




        if (!$product) {
            return $this->redirectToRoute('error');
        }
    
       
        if (!$user) {
            return $this->redirectToRoute('error');
        }
    
        $form = $this->createForm(QuantityFormType::class, null, [
            'product_quantity' => $product->getQuantite(),
        ]);
 
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
          
            $quantity = $form->get('quantiteproduct')->getData();
    
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
    

    #[Route('/my-cart', name: 'my_cart')]
    public function myCart(CartsRepository $cartsRepository, SouscartsRepository $souscartsRepository): Response
    {
        $user = $this->getDoctrine()->getRepository(Users::class)->find(10);
        if (!$user) {
            throw $this->createNotFoundException('User not found.');
        }

        $userCart = $cartsRepository->findOneBy(['owner' => $user, 'isconfirmed' => 0]);
        if (!$userCart) {
            $this->addFlash('error', 'You Don t Have a Cart.');
            return $this->redirectToRoute('showProducts');
        }
        $userSouscarts = $souscartsRepository->findBy(['cart' => $userCart]);
        return $this->render('products/frontOffice/CartsProductPage.html.twig', [
            "userCart" => $userCart,
            'souscarts' => $userSouscarts,
        ]);
    }

    #[Route('/deletesoucart/{idSouscarts}', name: "deletesoucart")]
    public function deletesoucart(int $idSouscarts, EntityManagerInterface $entityManager): Response
    {
        $souscart = $entityManager->getRepository(Souscarts::class)->find($idSouscarts);
        if (!$souscart) {
            throw $this->createNotFoundException('Entity not found');
        }
        
        $cart = $souscart->getCart();


        $entityManager->remove($souscart);
       // $cart->updateTotalPrice();
        $entityManager->flush();
      
        
        return $this->redirectToRoute('my_cart');   
    }
    
    
    #[Route('/DeleteCart/{idcarts}', name: "DeleteCart")]
    public function deletecarts(int $idcarts): Response
    {
        $cart = $this->getDoctrine()->getRepository(Carts::class)->find($idcarts);

        if (!$cart) {
            throw $this->createNotFoundException('Entity not found');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($cart);
        $em->flush();

        return $this->redirectToRoute('showProducts');
    }

    
    

    


}
