<?php

namespace App\Repository;

use App\Entity\Courses;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Courses>
 *
 * @method Courses|null find($id, $lockMode = null, $lockVersion = null)
 * @method Courses|null findOneBy(array $criteria, array $orderBy = null)
 * @method Courses[]    findAll()
 * @method Courses[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CoursesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Courses::class);
    }

    public function coursePagination(?int $idUser){
        $em = $this->getEntityManager();
        $query = $em->createQuery('
            SELECT c 
            FROM App\Entity\Courses c 
            WHERE NOT EXISTS (
                SELECT cp 
                FROM App\Entity\Courseparticipations cp 
                WHERE cp.participant = :idUser 
                AND cp.course = c.idcourse
            )
        ')
        ->setParameter('idUser', $idUser);
        return $query;
    }
    
    public function showCoursesHomePage(?int $idUser) {
        $em = $this->getEntityManager();
        $query = $em->createQuery('
            SELECT c 
            FROM App\Entity\Courses c 
            WHERE NOT EXISTS (
                SELECT cp 
                FROM App\Entity\Courseparticipations cp 
                WHERE cp.participant = :idUser 
                AND cp.course = c.idcourse
            )
        ')
        ->setParameter('idUser', $idUser)
        ->setMaxResults(6);
        return $query->getResult();
    }

    public function filterCoursesBack($idTutor,$filter){
        $queryBuilder = $this->createQueryBuilder('c')
        ->where('c.tutor = :idTutor')
        ->setParameter('idTutor', $idTutor);
        switch ($filter) {
            case 'Newest':
                $queryBuilder->orderBy('c.posteddate', 'DESC');
                break;
            case 'Oldest':
                $queryBuilder->orderBy('c.posteddate', 'ASC');
                break;
            case 'Most Registered':
                $queryBuilder->orderBy('c.nbrregistred', 'DESC');
                break;
            case 'Most Rated':
                $queryBuilder->orderBy('c.rate', 'DESC');
                break;
        }
        return $queryBuilder->getQuery()->getResult();    
    }

    public function searchCoursesBack($idTutor,$search){
        return $this->createQueryBuilder('c')
        ->where('c.tutor = :idTutor')
        ->andWhere('c.title LIKE :search')
        ->setParameters(['idTutor' => $idTutor, 'search' => '%' . $search . '%' ])
        ->getQuery()
        ->getResult();
    }

    public function filterCoursesFront(int $idUser, string $filter) {
        $em = $this->getEntityManager();
        $orderBy = '';
        switch ($filter) {
            case 'Newest':
                $orderBy = 'c.posteddate DESC';
                break;
            case 'Oldest':
                $orderBy = 'c.posteddate ASC';
                break;
            case 'Most Registered':
                $orderBy = 'c.nbrregistred DESC';
                break;
            case 'Most Rated':
                $orderBy = 'c.rate DESC';
                break;
            case 'Price':
                $orderBy = 'c.price DESC';
                break;
        }
        return $em->createQuery('
            SELECT c 
            FROM App\Entity\Courses c 
            WHERE NOT EXISTS (
                SELECT cp 
                FROM App\Entity\Courseparticipations cp 
                WHERE cp.participant = :idUser 
                AND cp.course = c.idcourse
            ) ORDER BY '.$orderBy
        )->setParameter('idUser', $idUser)->getResult();
    }

    public function searchCoursesFront(int $idUser, string $search) {
        $em = $this->getEntityManager();
        return $em->createQuery('
            SELECT c 
            FROM App\Entity\Courses c 
            WHERE NOT EXISTS (
                SELECT cp 
                FROM App\Entity\Courseparticipations cp 
                WHERE cp.participant = :idUser 
                AND cp.course = c.idcourse
            ) AND c.title LIKE :search'
        )->setParameters(['idUser'=> $idUser, 'search' => '%' . $search . '%'])->getResult();
    }
    
//    /**
//     * @return Courses[] Returns an array of Courses objects
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

//    public function findOneBySomeField($value): ?Courses
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
