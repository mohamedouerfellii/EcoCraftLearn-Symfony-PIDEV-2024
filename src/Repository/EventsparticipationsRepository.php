<?php

namespace App\Repository;

use App\Entity\Eventsparticipations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use App\Entity\Events;
use App\Entity\Users;
/**
 * @extends ServiceEntityRepository<Eventsparticipations>
 *
 * @method Eventsparticipations|null find($id, $lockMode = null, $lockVersion = null)
 * @method Eventsparticipations|null findOneBy(array $criteria, array $orderBy = null)
 * @method Eventsparticipations[]    findAll()
 * @method Eventsparticipations[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventsparticipationsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Eventsparticipations::class);
    }

    public function updatePlaces(int $idparticipation): int
    {
        $qb = $this->createQueryBuilder('ep');
        $qb->select('IDENTITY(ep.event) AS idevent') 
            ->where('ep.idparticipation = :idparticipation')
            ->setParameter('idparticipation', $idparticipation);
    
        $idevent = $qb->getQuery()->getSingleScalarResult();
    
        if (!$idevent) {
            
            return 0;
        }
    
    
        $qb = $this->createQueryBuilder('e');
        $qb->update('App\Entity\Events', 'e')
            ->set('e.placenbr', 'e.placenbr + 1') 
            ->where('e.idevent = :idevent')
            ->setParameter('idevent', $idevent);
    
        return $qb->getQuery()->execute();
    }
    

    public function findParticipantsByEventId(int $idevent): array
    {
        $entityManager = $this->getEntityManager();
    
        $query = $entityManager->createQuery(
            'SELECT ep
            FROM App\Entity\Eventsparticipations ep
            JOIN ep.participant u
            JOIN ep.event e
            WHERE e.idevent = :idevent'
        )->setParameter('idevent', $idevent);
    
        return $query->getResult();
    }
    
    
    
    public function countParticipantsForEvent($eventId): int
    {
        return $this->createQueryBuilder('ep')
            ->select('COUNT(ep)')
            ->where('ep.event = :eventId')
            ->setParameter('eventId', $eventId)
            ->getQuery()
            ->getSingleScalarResult();
    }
  
    
    
    
    
    
}
