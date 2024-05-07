<?php

namespace App\Repository;

use App\Entity\Posts;
use App\Entity\Postslikes;
use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PostsLikesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Postslikes::class);
    }

    // Add custom repository methods here, if needed
    public function likeExist(Posts $post, Users $user): ?PostsLikes
    {
        return $this->createQueryBuilder('pl')
            ->andWhere('pl.post = :post')
            ->andWhere('pl.user = :user')
            ->setParameter('post', $post)
            ->setParameter('user', $user)
            ->getQuery()
            ->getOneOrNullResult();
    }
    public function likeByUser(Users $user): array
    {
        return $this->createQueryBuilder('pl')
            ->andWhere('pl.idUser = :user')
            ->andWhere('pl.action = :action')
            ->setParameter('user', $user)
            ->setParameter('action', 1)
            ->getQuery()
            ->getResult();
    }
}
