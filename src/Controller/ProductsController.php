<?php

namespace App\Controller;
use Endroid\QrCode\QrCode;

use App\Entity\Products;
use App\Entity\Users;
use App\Form\FormProductType;
use App\Repository\ProductsRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ProductsevaluationsRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Productsevaluations;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;
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
        $user = $this->getDoctrine()->getRepository(Users::class)->find(8);
    
        $products = $rep->findConfirmedProductsByOwner($user);
    
        return $this->render("products/frontOffice/MyProductPage.html.twig", ["Products" => $products]);
    }

    #[Route('/showProducts', name: 'showProducts')]
    public function show(
        ProductsRepository $rep,
        SouscartsRepository $souscartsRepository,
        CommandesRepository $commandesRepository,
        ProductsevaluationsRepository $repository
    ): Response {
        $user = $this->getDoctrine()->getRepository(Users::class)->find(12);
    
        if (!$user) {
            throw $this->createNotFoundException('User not found.');
        }
    
        $souscartCount = $souscartsRepository->countByOwner($user);
        $orderCount = $commandesRepository->countByOwner($user);
        $products = $rep->findProductsExceptOwner($user);
    
        $moyRates = [];
    
        foreach ($products as $product) {
            $idproduct = $product->getIdproduct();
    
            $countrate = $repository->countRatingsByProductId($idproduct);
            $sumrate = $repository->sumRatingByProductId($idproduct);
            $moyRate = $countrate > 0 ? $sumrate / $countrate : 0;
    
            $moyRates[$idproduct] = $moyRate;
        }
    
        return $this->render("products/frontOffice/homePageProduct.html.twig", [
            "Products" => $products,
            "souscartCount" => $souscartCount,
            "orderCount" => $orderCount,
            'moyRates' => $moyRates,
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
    public function addProduct(ManagerRegistry $doctrine,Request $request,SluggerInterface $slugger)
    {


        $product = new Products();
        $form = $this->createForm(FormProductType::class, $product);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $doctrine->getManager();
            $product->setOwner($em->getRepository(Users::class)->find(8));

            $productImage = $form->get('image')->getData();
            if ($productImage) {
                $originalFilename = pathinfo($productImage->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $productImage->guessExtension();
                try {
                    $productImage->move(
                        $this->getParameter('products_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $e->getMessage();
                }
                $product->setImage($newFilename);
            }
            $em->persist($product);
            $em->flush();
            return $this->redirectToRoute('MyProduct');
        }

        $products = $doctrine->getManager()->getRepository(Products::class)->findAll();
        return $this->render('products/frontOffice/AddNewProductForm.html.twig', [
            'form' => $form->createView(),
            'products' => $products,

        ]);

    }
    #[Route('/showProductsDetail/{idproduct}', name: 'showProductsDetail')]
    public function showProductsDetail($idproduct, ProductsevaluationsRepository $productsevaluationsRepository): Response
    {
       
        $product = $this->getDoctrine()->getRepository(Products::class)->findOneBy(['idproduct' => $idproduct]);
        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }

        $countrate = $productsevaluationsRepository->countRatingsByProductId($idproduct);
        $sumrate = $productsevaluationsRepository->sumRatingByProductId($idproduct);
        $moyRate = $countrate > 0 ? $sumrate / $countrate : 0;
    
     
        return $this->render('products/frontOffice/DetailProductPage.html.twig', [
            'product' => $product,
            'countrate' => $countrate,
            'moyRate' => $moyRate,
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
        public function updateproduct(ManagerRegistry $doctrine,Request $request,SluggerInterface $slugger)
        {
            $em = $doctrine->getManager();
            $product = $em->getRepository(Products::class)->find($request->get('idproduct'));
            $form = $this->createForm(FormProductType::class,$product);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()){
                $productImage = $form->get('image')->getData();
                if ($productImage) {
                    $originalFilename = pathinfo($productImage->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $productImage->guessExtension();
                    try {
                        $productImage->move(
                            $this->getParameter('products_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        $e->getMessage();
                    }
                    $product->setImage($newFilename);
                }
                $em->persist($product);
                $em->flush();
                return $this->redirectToRoute('showProductsDetail', ['idproduct' => $product->getIdproduct()]);
                }
                return $this->render('products/frontOffice/UpdateProductForm.html.twig', [
                    'form' => $form->createView(),  
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

        #[Route('/searchProducts', name: 'search_Product_Front')]
        public function searchProduct(ProductsRepository $productsRep, Request $request): Response
        {

            $owner = 12;
            $search = $request->query->get('search');
            $products = $productsRep->searchProductByNameAndOwner ($search,$owner);
    
            $dataToJson = $this->serializeProducts($products);
            
            return new Response($dataToJson, Response::HTTP_OK, ['Content-Type' => 'application/json']);
        }

        

        #[Route('/filterProduct', name: 'filter_Product')]
        public function filterProduct(ProductsRepository $productsRep, Request $request): Response
        {
            $owner = 12; 
            $filter = $request->query->get('filter');
            $products = $productsRep->filterProductbyDate($owner, $filter); 
        
            $dataToJson = $this->serializeProducts($products);
        
            return new Response($dataToJson, Response::HTTP_OK, ['Content-Type' => 'application/json']);
        }
        
        private function serializeProducts(array $products): string
        {
            $serializer = $this->get('serializer');
            return $serializer->serialize($products, 'json');
        }





}
