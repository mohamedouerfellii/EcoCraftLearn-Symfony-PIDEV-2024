<?php

namespace App\Entity;

use App\Repository\PostsLikesRepository;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: PostsLikesRepository::class)]
class Postslikes
{
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "idLike", type: "integer", nullable: false)]
    private ?int $idLike = null;

    #[ORM\Column]
    private ?int $action = 0;

    #[ORM\ManyToOne(targetEntity: Users::class)]
    #[ORM\JoinColumn(name: "idUser", referencedColumnName: "iduser", onDelete: "CASCADE")]
    private ?Users $idUser = null;

    #[ORM\ManyToOne(targetEntity: Posts::class)]
    #[ORM\JoinColumn(name: "Post", referencedColumnName: "idPost", onDelete: "CASCADE")]
    private ?Posts $post = null;

    public function getIdLike(): ?int
    {
        return $this->idLike;
    }

    public function getAction(): ?int
    {
        return $this->action;
    }

    public function setAction(int $action): self
    {
        $this->action = $action;
        return $this;
    }

    public function getUser(): ?Users
    {
        return $this->idUser;
    }

    public function setUser(?Users $user): self
    {
        $this->idUser = $user;
        return $this;
    }

    public function getPost(): ?Posts
    {
        return $this->post;
    }

    public function setPost(?Posts $post): self
    {
        $this->post = $post;
        return $this;
    }


}