<?php

namespace App\Repository;

use App\Entity\Events;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Events>
 *
 * @method Events|null find($id, $lockMode = null, $lockVersion = null)
 * @method Events|null findOneBy(array $criteria, array $orderBy = null)
 * @method Events[]    findAll()
 * @method Events[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Events::class);
    }

    public function updatePlaces(int $idevent): int
    {
        $qb = $this->createQueryBuilder('e');
        $qb->update('App\Entity\Events', 'e')
            ->set('e.placenbr', 'e.placenbr - 1')
            ->where('e.idevent = :idevent')
            ->andWhere('e.placenbr > 0')
            ->setParameter('idevent', $idevent);

        return $qb->getQuery()->execute();
    }

    public function updatePlacescancled(int $idevent): int
{
    $qb = $this->createQueryBuilder('e');
    $qb->update('App\Entity\Events', 'e')
        ->set('e.placenbr', 'e.placenbr + 1') 
        ->where('e.idevent = :idevent')
        ->andWhere('e.placenbr > 0')
        ->setParameter('idevent', $idevent);

    return $qb->getQuery()->execute();
}



public function searchEventByTitle(string $search)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.title LIKE :search')
            ->setParameter('search', '%' . $search . '%')
            ->getQuery()
            ->getResult();
    }


    public function filterByDateDesc(string $filter)
    {
        $queryBuilder = $this->createQueryBuilder('e');
        switch ($filter) {
            case 'Newest':
                $queryBuilder->orderBy('e.startdate', 'DESC');
                break;
            case 'Oldest':
                $queryBuilder->orderBy('e.startdate', 'ASC');
                break;
            case 'Expensive':
                $queryBuilder->orderBy('e.price', 'DESC');
                break;
            case 'Shipping':
                $queryBuilder->orderBy('e.price', 'ASC');
                break;
            default:
                throw new \InvalidArgumentException('Invalid filter provided.');
        }
        return $queryBuilder->getQuery()->getResult();    
    }
    

  
    
    
    
    
    




    
}
