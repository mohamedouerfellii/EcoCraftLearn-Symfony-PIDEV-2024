<?php

namespace App\Repository;

use App\Entity\Quizzes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Quizzes>
 *
 * @method Quizzes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Quizzes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Quizzes[]    findAll()
 * @method Quizzes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuizzesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Quizzes::class);
    }
    
    public function getQuizBySection(?int $idSection){
        return $this->createQueryBuilder('q')
        ->where('q.section = :idSection')
        ->setParameter('idSection', $idSection)
        ->getQuery()
        ->getOneOrNullResult();
    }

    public function getLastId(){
        return $this->createQueryBuilder('q')
            ->orderBy('q.idquiz', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }    

//    /**
//     * @return Quizzes[] Returns an array of Quizzes objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('q.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Quizzes
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
