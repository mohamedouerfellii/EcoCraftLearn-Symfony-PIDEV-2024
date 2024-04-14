<?php

namespace App\Repository;

use App\Entity\Quizquestions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Quizquestions>
 *
 * @method Quizquestions|null find($id, $lockMode = null, $lockVersion = null)
 * @method Quizquestions|null findOneBy(array $criteria, array $orderBy = null)
 * @method Quizquestions[]    findAll()
 * @method Quizquestions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuizquestionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Quizquestions::class);
    }

    public function getQuestionsByQuiz(?int $idQuiz){
        return $this->createQueryBuilder('qq')
            ->where('qq.quiz = :idQuiz')
            ->setParameter('idQuiz', $idQuiz)
            ->getQuery()
            ->getResult();
    }
//    /**
//     * @return Quizquestions[] Returns an array of Quizquestions objects
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

//    public function findOneBySomeField($value): ?Quizquestions
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
