<?php

namespace App\Repository;

use App\Entity\Courseparticipations;
use App\Entity\Courses;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Courseparticipations>
 *
 * @method Courseparticipations|null find($id, $lockMode = null, $lockVersion = null)
 * @method Courseparticipations|null findOneBy(array $criteria, array $orderBy = null)
 * @method Courseparticipations[]    findAll()
 * @method Courseparticipations[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CourseparticipationsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Courseparticipations::class);
    }

    public function findCpByUserCourse(int $idCourse, int $idUser){
        return $this->createQueryBuilder('cp')
        ->where('cp.participant = :idUser')
        ->andWhere('cp.course = :idCourse')
        ->setParameters(['idUser' => $idUser, 'idCourse' => $idCourse ])
        ->getQuery()
        ->getSingleResult();
    }

    public function getParticipantsMail(int $idCourse){
        $query = $this->createQueryBuilder('cp')
            ->select('u.email')
            ->join('cp.participant', 'u')
            ->where('cp.course = :idCourse')
            ->setParameter('idCourse', $idCourse)
            ->getQuery();

        $results = $query->getResult();
        $emails = array_column($results, 'email'); // Extracting emails from the result array

        return $emails;
    }

//    /**
//     * @return Courseparticipations[] Returns an array of Courseparticipations objects
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

//    public function findOneBySomeField($value): ?Courseparticipations
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
