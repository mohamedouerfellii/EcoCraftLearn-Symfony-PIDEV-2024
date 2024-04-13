<?php

namespace App\Controller;

use App\Entity\Products;
use App\Entity\Users;
use App\Form\FormProductType;
use App\Repository\ProductsRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;

use App\Repository\SouscartsRepository;
use App\Repository\CommandesRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
class ProductsController extends AbstractController
{
    #[Route('/products', name: 'app_products')]
    public function index(): Response
    {
        return $this->render('products/frontOffice/homePageProduct.html.twig', [
            'controller_name' => 'ProductsController',
        ]);
    }

   
    #[Route('/ProductsDashboard', name: 'ProductsDashboard')]
    public function Dashboard(): Response
    {
        return $this->render('products/backOffice/ProductDashboard.html.twig', [
            'controller_name' => 'ProductsController',
        ]);
    }

    #[Route('/MyProduct', name: 'MyProduct')]
    public function showMyProduct(ProductsRepository $rep): Response
    {
        $user = $this->getDoctrine()->getRepository(Users::class)->find(10);
    
        $products = $rep->findConfirmedProductsByOwner($user);
    
        return $this->render("products/frontOffice/MyProductPage.html.twig", ["Products" => $products]);
    }

    #[Route('/showProducts', name: 'showProducts')]
    public function show(ProductsRepository $rep, SouscartsRepository $souscartsRepository, CommandesRepository $commandesRepository): Response
    {
        $user = $this->getDoctrine()->getRepository(Users::class)->find(12);

        if (!$user) {
            throw $this->createNotFoundException('User not found.');
        }

        $souscartCount = $souscartsRepository->countByOwner($user);
        $orderCount = $commandesRepository->countByOwner($user);

        $products = $rep->findProductsExceptOwner($user);

        return $this->render("products/frontOffice/homePageProduct.html.twig", [
            "Products" => $products,
            "souscartCount" => $souscartCount,
            "orderCount" => $orderCount
        ]);
    }


     #[Route('/cart-and-orders-count', name: 'cart_and_orders_count')]
    public function cartAndOrdersCount(SouscartsRepository $souscartsRepository, CommandesRepository $commandesRepository): JsonResponse
    {
        $user = $this->getDoctrine()->getRepository(Users::class)->find(10);
        if (!$user) {
            throw $this->createNotFoundException('User not found.');
        }
        $souscartCount = $souscartsRepository->countByOwner($user);
        $orderCount = $commandesRepository->countByOwner($user);

        return new JsonResponse([
            'souscartCount' => $souscartCount,
            'orderCount' => $orderCount,
        ]);
    }























        #[Route('/Add-Product', name: 'AddProduct')]
        public function AddProduct(ManagerRegistry $doctrine, Request $request, ProductsRepository $rep): Response
        {

            $entityManager = $this->getDoctrine()->getManager();
            $user = $entityManager->getRepository(Users::class)->find(12);
            $product = new Products();
            $product->setOwner($user);
            $form = $this->createForm(FormProductType::class, $product);
            $form->handleRequest($request);
        
            if ($form->isSubmitted()) {
                $em = $doctrine->getManager();
                $em->persist($product);
                $em->flush();
                
                return $this->redirectToRoute('MyProduct');
            }
        
            $products = $rep->findAll();
        
            return $this->render('products/frontOffice/AddNewProductForm.html.twig', [
                'form' => $form->createView(),
                'Products' => $products, 
            ]);
        }


        #[Route('/showProductsDetail/{idproduct}', name: 'showProductsDetail')]
        public function showProductsDetail($idproduct): Response
        {
            $product = $this->getDoctrine()->getRepository(Products::class)->findOneBy(['idproduct' => $idproduct]);
    
            if (!$product) {
                throw $this->createNotFoundException('product not found');
            }
            return $this->render('products/frontOffice/DetailProductPage.html.twig', [
                'product' => $product,
            ]);
        }




        #[Route('/deleteProduct/{idproduct}', name: "deleteProduct")]
        public function deleteproduct(int $idproduct): Response
        {
            $product = $this->getDoctrine()->getRepository(Products::class)->find($idproduct);
    
            if (!$product) {
                throw $this->createNotFoundException('Entity not found');
            }
    
            $em = $this->getDoctrine()->getManager();
            $em->remove($product);
            $em->flush();
    
            return $this->redirectToRoute('MyProduct');
        }
    
    
    
        #[Route('/updateproduct/{idproduct}', name: "updateproduct")]
        public function updateproduct(Request $request, int $idproduct): Response
        {
            $entityManager = $this->getDoctrine()->getManager();
            $task = $entityManager->getRepository(Products::class)->find($idproduct);
    
            if (!$task) {
                throw $this->createNotFoundException('Task not found');
            }
    
            $form = $this->createForm(FormProductType::class, $task);
            $form->handleRequest($request);
    
            if ($form->isSubmitted()) {
             
                $entityManager->flush();
                return $this->redirectToRoute('MyProduct');
            }
            return $this->render('products/frontOffice/UpdateProductForm.html.twig', [
                'form' => $form->createView(),
                //'Product' => $product, 
            ]);
        }


        #[Route('/showProductsDashboard', name: 'showProductsDashboard')]
	    public function showAllProductDashbord(ProductsRepository $rep): Response
	    {
	        $products = $rep->findUnconfirmedProducts();
            return $this->render("products/backOffice/ProductDashboardAdmin.html.twig", ["Products" => $products]);
	    }


        #[Route('/AcceptedProduct/{idproduct}', name: 'AcceptedProduct')]
        public function confirmProduct(ProductsRepository $productsRepository, int $idproduct): Response
        {
            $productsRepository->confirmProduct($idproduct);

            return $this->redirectToRoute('showProductsDashboard');
        }
    


   






}
