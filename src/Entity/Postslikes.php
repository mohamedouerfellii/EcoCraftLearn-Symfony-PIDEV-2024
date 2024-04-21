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

    public function getIdlike(): ?int
    {
        return $this->idlike;
    }

    public function getAction(): ?int
    {
        return $this->action;
    }

    public function setAction(int $action): static
    {
        $this->action = $action;

        return $this;
    }

    public function getIdUser(): ?Users
    {
        return $this->idUser;
    }

    public function setIdUser(?Users $idUser): static
    {
        $this->idUser = $idUser;

        return $this;
    }

    public function getPost(): ?Users
    {
        return $this->post;
    }

    public function setPost(?Users $post): static
    {
        $this->post = $post;

        return $this;
    }


}