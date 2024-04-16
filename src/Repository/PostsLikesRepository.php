<?php
namespace App\Repository;

use App\Entity\Postslikes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PostsLikesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Postslikes::class);
    }

    // Add custom repository methods here, if needed
}