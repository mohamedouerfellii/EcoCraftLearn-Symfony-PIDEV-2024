<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Posts;
use App\Entity\Users;
use App\Service\SearchData;
use Symfony\Component\Security\Core\User\User;

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


    public function findByName(string $search)
    {
        return $this->createQueryBuilder('p')
            ->where('p.content LIKE :search')
            ->setParameter('search', '%' . $search . '%')
            ->getQuery()
            ->getResult();
    }
    public function findBySearch(SearchData $searchData): array
{
    $queryBuilder = $this->createQueryBuilder('p');

    if (!empty($searchData->contents)) {
        $queryBuilder
            ->where('p.content LIKE :search')
            ->setParameter('search', '%' . $searchData->contents . '%');
    }

    return $queryBuilder->getQuery()->getResult();
}
public function isCurrentUserLikedByUser(Users $currentUser, Users $likedUser): bool
    {
        $queryBuilder = $this->createQueryBuilder('l');
        $queryBuilder
            ->andWhere('l.user = :currentUser')
            ->andWhere('l.likedByUser = :likedUser')
            ->setParameter('currentUser', $currentUser)
            ->setParameter('likedUser', $likedUser);

        $result = $queryBuilder->getQuery()->getResult();

        // If there are any likes found, it means the current user is liked by the liked user
        return !empty($result);
    }
}