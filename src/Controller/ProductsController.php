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
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Security\Core\Security;

class ProductsController extends AbstractController
{
    #[Route('/student/products', name: 'app_products')]
    public function index(): Response
    {
        return $this->render('products/frontOffice/homePageProduct.html.twig', [
            'controller_name' => 'ProductsController',
        ]);
    }

   
    #[Route('/admin/ProductsDashboard', name: 'ProductsDashboard')]
    public function Dashboard(): Response
    {
        return $this->render('products/backOffice/ProductDashboard.html.twig', [
            'controller_name' => 'ProductsController',
        ]);
    }

    #[Route('/student/MyProduct', name: 'MyProduct')]
    public function showMyProduct(ProductsRepository $rep, ManagerRegistry $doctrine): Response
    {

        $user = $doctrine->getManager()->getRepository(Users::class)->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
       
        $products = $rep->findConfirmedProductsByOwner($user);
    
        return $this->render("products/frontOffice/MyProductPage.html.twig", ["Products" => $products]);
    }

    #[Route('/student/showProducts', name: 'showProducts')]
    public function show(
        ProductsRepository $rep,
        SouscartsRepository $souscartsRepository,
        CommandesRepository $commandesRepository,
        ProductsevaluationsRepository $repository,
        ManagerRegistry $doctrine
    ): Response {
        $user = $doctrine->getManager()->getRepository(Users::class)->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
    
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
    


     #[Route('/student/cart-and-orders-count', name: 'cart_and_orders_count')]
    public function cartAndOrdersCount(SouscartsRepository $souscartsRepository, CommandesRepository $commandesRepository, ManagerRegistry $doctrine): JsonResponse
    {
        $user = $doctrine->getManager()->getRepository(Users::class)->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
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

    #[Route('/student/Add-Product', name: 'AddProduct')]
    public function addProduct(ManagerRegistry $doctrine,Request $request,SluggerInterface $slugger )
    {


        $product = new Products();
        $form = $this->createForm(FormProductType::class, $product);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $doctrine->getManager();
            $user = $doctrine->getManager()->getRepository(Users::class)->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);

            $product->setOwner($user);

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
    #[Route('/student/showProductsDetail/{idproduct}', name: 'showProductsDetail')]
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
    


        #[Route('/student/deleteProduct/{idproduct}', name: "deleteProduct")]
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
    
    

        #[Route('/student/updateproduct/{idproduct}', name: "updateproduct")]
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




        #[Route('/admin/showProductsDashboard', name: 'showProductsDashboard')]
	    public function showAllProductDashbord(ProductsRepository $rep): Response
	    {
	        $products = $rep->findUnconfirmedProducts();
            return $this->render("products/backOffice/ProductDashboardAdmin.html.twig", ["Products" => $products]);
	    }

        

        #[Route('/admin/AcceptedProduct/{idproduct}', name: 'AcceptedProduct')]
        public function confirmProduct(ProductsRepository $productsRepository, int $idproduct): Response
        {
            $productsRepository->confirmProduct($idproduct);

            return $this->redirectToRoute('showProductsDashboard');
        }

        #[Route('/student/searchProducts', name: 'search_Product_Front')]
        public function searchProduct(ProductsRepository $productsRep, Request $request,Security $security): Response
        {

            if($security->getUser() instanceof Users)
            $user = $security->getUser();
            $owner = $user->getIdUser();
            $search = $request->query->get('search');
            $products = $productsRep->searchProductByNameAndOwner ($search,$owner);
    
            $dataToJson = $this->serializeProducts($products);
            
            return new Response($dataToJson, Response::HTTP_OK, ['Content-Type' => 'application/json']);
        }

        

#[Route('/student/filterProduct', name: 'filter_Product')]
public function filterProduct(ProductsRepository $productsRep, Request $request,Security $security): Response
{
    if($security->getUser() instanceof Users)
    $user = $security->getUser();
    $owner = $user->getIdUser();
    $filter = $request->query->get('filter');
    
   
    if ($filter === 'Newest' || $filter === 'Oldest' || $filter === 'Expensive' || $filter === 'Cheap') {
        $products = $productsRep->filterProductByPriceAnddate($owner, $filter);
    }
    else {
        return new Response('Invalid filter parameter', Response::HTTP_BAD_REQUEST);
    }
    $dataToJson = $this->serializeProducts($products);
    return new Response($dataToJson, Response::HTTP_OK, ['Content-Type' => 'application/json']);
}
private function serializeProducts(array $products): string
{
    $serializer = $this->get('serializer');
    return $serializer->serialize($products, 'json');
}





}
