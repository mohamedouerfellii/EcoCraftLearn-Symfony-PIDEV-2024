<?php
//******* */
namespace App\Repository;

use App\Entity\Collectspts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Collectspts>
 *
 * @method |null find($id, $lockMode = null, $lockVersion = null)
 * @method |null findOneBy(array $criteria, array $orderBy = null)
 * @method []    findAll()
 * @method []    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CollectsptsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Collectspts::class);
    }

    public function save(Collectspts $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Collectspts $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
 





  

}
