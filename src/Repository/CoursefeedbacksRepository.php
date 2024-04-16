<?php

namespace App\Repository;

use App\Entity\Coursefeedbacks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Coursefeedbacks>
 *
 * @method Coursefeedbacks|null find($id, $lockMode = null, $lockVersion = null)
 * @method Coursefeedbacks|null findOneBy(array $criteria, array $orderBy = null)
 * @method Coursefeedbacks[]    findAll()
 * @method Coursefeedbacks[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CoursefeedbacksRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Coursefeedbacks::class);
    }

//    /**
//     * @return Coursefeedbacks[] Returns an array of Coursefeedbacks objects
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

//    public function findOneBySomeField($value): ?Coursefeedbacks
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
