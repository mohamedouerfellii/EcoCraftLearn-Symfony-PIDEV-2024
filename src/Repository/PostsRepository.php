<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Posts;

class PostsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Posts::class);
    }
    
    // src/Repository/PostsRepository.php
    public function findAllPostsAndComments()
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.comments', 'c')
            ->addSelect('c')
            ->getQuery()
            ->getResult();
    }
    public function searchByString(string $searchString)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.content LIKE :search')
            ->setParameter('search', '%'.$searchString.'%')
            ->getQuery()
            ->getResult();
    }
}