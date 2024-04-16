<?php

namespace App\Entity;

use App\Repository\PostsLikesRepository;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: PostsLikesRepository::class)]
class Postslikes
{
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idlike=null;

    #[ORM\Column]
    private ?int $action = 0;

    #[ORM\ManyToOne(targetEntity: Users::class)]
    #[ORM\JoinColumn(name: "idUser", referencedColumnName: "idUser", onDelete: "CASCADE")]
    
    private ?Users $idUser=null;

    #[ORM\ManyToOne(targetEntity: Users::class)]
    #[ORM\JoinColumn(name: "idPost", referencedColumnName: "idPost", onDelete: "CASCADE")]
    private ?Posts $post=null;


}