<?php

namespace App\Repository;

use App\Entity\Collects;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Collects>
 *
 * @method Collects|null find($id, $lockMode = null, $lockVersion = null)
 * @method Collects|null findOneBy(array $criteria, array $orderBy = null)
 * @method Collects[]    findAll()
 * @method Collects[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CollectsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Collects::class);
    }

    public function save(Collects $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Collects $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    

    public function searchByTypeOrNameOrComment($searchTerm)
{
    $qb = $this->createQueryBuilder('r');
    if ($searchTerm) {
        $qb->andWhere($qb->expr()->orX(
            $qb->expr()->like('r.materialType', ':searchTerm'),
            $qb->expr()->like('r.quantity', ':searchTerm'),
            
        ))
        ->setParameter('searchTerm', '%' . $searchTerm . '%');
    }
    
    return $qb->getQuery()->getResult();
}
   


}
