<?php

namespace App\Repository;

use App\Entity\Souscarts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Souscarts>
 *
 * @method Souscarts|null find($id, $lockMode = null, $lockVersion = null)
 * @method Souscarts|null findOneBy(array $criteria, array $orderBy = null)
 * @method Souscarts[]    findAll()
 * @method Souscarts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SouscartsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Souscarts::class);
    }

//    /**
//     * @return Souscarts[] Returns an array of Souscarts objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Souscarts
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
