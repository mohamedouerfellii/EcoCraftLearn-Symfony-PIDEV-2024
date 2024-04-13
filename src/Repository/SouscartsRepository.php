<?php

namespace App\Repository;

use App\Entity\Souscarts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Users;



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

    public function countByOwner(Users $user): int
    {
        return $this->createQueryBuilder('s')
            ->select('COUNT(s)')
            ->leftJoin('s.cart', 'c')
            ->andWhere('c.owner = :user')
            ->andWhere('c.isconfirmed = :confirmed')
            ->setParameter('user', $user)
            ->setParameter('confirmed', 0)
            ->getQuery()
            ->getSingleScalarResult();
    }
    
    
}
