<?php

namespace App\Repository;

use App\Entity\Users;
use App\Entity\Products;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;




/**
 * @extends ServiceEntityRepository<Products>
 *
 * @method Products|null find($id, $lockMode = null, $lockVersion = null)
 * @method Products|null findOneBy(array $criteria, array $orderBy = null)
 * @method Products[]    findAll()
 * @method Products[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Products::class);
    }




public function findConfirmedProductsByOwner(Users $user): array
{
    return $this->createQueryBuilder('p')
        ->andWhere('p.isconfirmed = :isconfirmed')
        ->andWhere('p.owner = :user')
        ->setParameter('isconfirmed', 1)
        ->setParameter('user', $user)
        ->getQuery()
        ->getResult();
}

public function findProductsExceptOwner(Users $user): array
{
    return $this->createQueryBuilder('p')
        ->andWhere('p.owner != :user')
        ->andWhere('p.isconfirmed = :confirmed')
        ->setParameter('user', $user)
        ->setParameter('confirmed', 1)
        ->getQuery()
        ->getResult();
}

public function findUnconfirmedProducts(): array
{
    return $this->createQueryBuilder('p')
        ->andWhere('p.isconfirmed = :confirmed')
        ->setParameter('confirmed', 0)
        ->getQuery()
        ->getResult();
}



    public function confirmProduct(int $idproduct): void
    {
        $entityManager = $this->getEntityManager();
        $product = $entityManager->getRepository(Products::class)->find($idproduct);

        if ($product) {
            $product->setIsConfirmed(1);
            $entityManager->flush();
        }
    }

    public function decrementProductQuantity($idcarts)
    {
        $entityManager = $this->getEntityManager();
        $souscarts = $entityManager->getRepository('App\Entity\Souscarts')->findBy([
            'cart' => $idcarts,
        ]);
    
        foreach ($souscarts as $souscart) {
            $product = $souscart->getProduct();
            $quantity = $souscart->getQuantiteproduct();


            if ($product && $quantity > 0) {
                $currentQuantity = $product->getQuantite();
                $newQuantity = max(0, $currentQuantity - $quantity);
                $product->setQuantite($newQuantity);
                $entityManager->persist($product);
            }

        }
        $entityManager->flush();
    }

    
  


    
    
    
    
    





}
