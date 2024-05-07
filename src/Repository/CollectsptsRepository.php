<?php

namespace App\Repository;

use App\Entity\Collectspts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Collectspts>
 *
 * @method Collectspts|null find($id, $lockMode = null, $lockVersion = null)
 * @method Collectspts|null findOneBy(array $criteria, array $orderBy = null)
 * @method Collectspts[]    findAll()
 * @method Collectspts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CollectsptsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Collectspts::class);
    }

//    /**
//     * @return Collectspts[] Returns an array of Collectspts objects
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

//    public function findOneBySomeField($value): ?Collectspts
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
