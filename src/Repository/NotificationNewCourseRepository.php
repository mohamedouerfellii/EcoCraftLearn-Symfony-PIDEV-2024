<?php

namespace App\Repository;

use App\Entity\NotificationNewCourse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NotificationNewCourse>
 *
 * @method NotificationNewCourse|null find($id, $lockMode = null, $lockVersion = null)
 * @method NotificationNewCourse|null findOneBy(array $criteria, array $orderBy = null)
 * @method NotificationNewCourse[]    findAll()
 * @method NotificationNewCourse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NotificationNewCourseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NotificationNewCourse::class);
    }

    public function getNotCheckedNotif(int $idTutor){
        return $this->createQueryBuilder('n')
            ->where('n.isChecked = false')
            ->andWhere('n.tutor_notified = :idTutor')
            ->setParameter('idTutor', $idTutor)
            ->getQuery()
            ->getResult();
    }

    public function getAllNotifications(int $idTutor){
        return $this->createQueryBuilder('n')
            ->where('n.tutor_notified = :idTutor')
            ->setParameter('idTutor', $idTutor)
            ->getQuery()
            ->getResult();
    }
//    /**
//     * @return NotificationNewCourse[] Returns an array of NotificationNewCourse objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('n.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?NotificationNewCourse
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
