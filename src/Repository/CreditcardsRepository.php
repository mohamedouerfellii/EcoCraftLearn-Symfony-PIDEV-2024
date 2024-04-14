<?php

namespace App\Repository;

use App\Entity\Creditcards;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Creditcards>
 *
 * @method Creditcards|null find($id, $lockMode = null, $lockVersion = null)
 * @method Creditcards|null findOneBy(array $criteria, array $orderBy = null)
 * @method Creditcards[]    findAll()
 * @method Creditcards[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CreditcardsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Creditcards::class);
    }

//    /**
//     * @return Creditcards[] Returns an array of Creditcards objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Creditcards
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
