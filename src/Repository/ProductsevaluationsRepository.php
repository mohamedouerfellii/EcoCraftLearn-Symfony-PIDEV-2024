<?php

namespace App\Repository;

use App\Entity\Products;
use App\Entity\Productsevaluations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
/**
 * @extends ServiceEntityRepository<Productsevaluations>
 *
 * @method Productsevaluations|null find($id, $lockMode = null, $lockVersion = null)
 * @method Productsevaluations|null findOneBy(array $criteria, array $orderBy = null)
 * @method Productsevaluations[]    findAll()
 * @method Productsevaluations[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductsevaluationsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Productsevaluations::class);
    }

    public function findByProduct(Products $product): array
    {
        return $this->createQueryBuilder('pe')
            ->andWhere('pe.product = :product')
            ->setParameter('product', $product)
            ->getQuery()
            ->getResult();
    }


    public function findByProductCalculateAverageRateAndTotalCount(Product $product): ?array
    {
        $idproduct = $product->getIdproduct();
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT AVG(pe.rate) AS averageRate, COUNT(pe.id) AS totalCount
            FROM App\Entity\Productsevaluations pe
            WHERE pe.product = :idproduct'
        )->setParameter('idproduct', $idproduct);
        $result = $query->getOneOrNullResult();
        if (!$result) {
            return null;
        }
        return [
            'averageRate' => $result['averageRate'],
            'totalCount' => $result['totalCount']
        ];
    }


    public function sumRatingByProductId($idproduct)
    {
        return $this->createQueryBuilder('pe')
            ->select('SUM(pe.rate)')
            ->andWhere('pe.product = :product')
            ->setParameter('product', $idproduct)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countRatingsByProductId($idproduct)
    {
        return $this->createQueryBuilder('pe')
            ->select('COUNT(pe.product)')
            ->andWhere('pe.product = :product')
            ->setParameter('product', $idproduct)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function checkIfUserIsEvaluatorForProduct($user, $product)
    {
        $qb = $this->createQueryBuilder('pe');
        $qb->select('COUNT(pe.idevaluation)')
            ->where('pe.evaluator = :user')
            ->andWhere('pe.product = :product')
            ->setParameter('user', $user)
            ->setParameter('product', $product);
            
        $count = $qb->getQuery()->getSingleScalarResult();
    
        return $count > 0;
    }
    

    


}
