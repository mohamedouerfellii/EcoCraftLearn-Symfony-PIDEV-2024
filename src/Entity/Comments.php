<?php

namespace App\Entity;

use App\Repository\CommentsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentsRepository::class)]
class Comments
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idcomment=null;

    #[ORM\Column]
    private ?string $content=null;

    #[ORM\Column]
    private ?string $attachment = null;

    #[ORM\ManyToOne(targetEntity: "Users")]
    #[ORM\JoinColumn(name: "post", referencedColumnName: "idPost", onDelete: "CASCADE")]
    private ?Posts $post=null;

    #[ORM\ManyToOne(targetEntity: "Users")]
    #[ORM\JoinColumn(name: "owner", referencedColumnName: "idUser", onDelete: "CASCADE")]
    private ?Users $owner=null;
    


}
