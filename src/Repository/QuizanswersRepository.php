<?php

namespace App\Repository;

use App\Entity\Quizanswers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Quizanswers>
 *
 * @method Quizanswers|null find($id, $lockMode = null, $lockVersion = null)
 * @method Quizanswers|null findOneBy(array $criteria, array $orderBy = null)
 * @method Quizanswers[]    findAll()
 * @method Quizanswers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuizanswersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Quizanswers::class);
    }

    public function getAnswerByStudentSection(int $idUser, int $idQuiz){
        return $this->createQueryBuilder('qa')
            ->where('qa.quizz = :idQuiz')
            ->andWhere('qa.student = :idUser')
            ->setParameters(['idQuiz' => $idQuiz,'idUser' => $idUser])
            ->getQuery()
            ->getOneOrNullResult();
    }
//    /**
//     * @return Quizanswers[] Returns an array of Quizanswers objects
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

//    public function findOneBySomeField($value): ?Quizanswers
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
