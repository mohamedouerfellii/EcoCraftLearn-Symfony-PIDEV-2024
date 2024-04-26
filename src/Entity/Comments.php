<?php

namespace App\Entity;

use App\Repository\CommentsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommentsRepository::class)]
class Comments
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idcomment = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'you need to write a Comment')]
    private ?string $content = null;
    

    #[ORM\Column]
    private ?string $attachment = null;

    #[ORM\ManyToOne(targetEntity: "Posts", inversedBy: "comments")]
    #[ORM\JoinColumn(name: "post", referencedColumnName: "idPost", onDelete: "CASCADE")]
    private ?Posts $post = null;

    #[ORM\ManyToOne(targetEntity: "Users")]
    #[ORM\JoinColumn(name: "owner", referencedColumnName: "idUser", onDelete: "CASCADE")]
    private ?Users $owner = null;

    public function getIdcomment(): ?int
    {
        return $this->idcomment;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getAttachment(): ?string
    {
        return $this->attachment;
    }

    public function setAttachment(string $attachment): static
    {
        $this->attachment = $attachment;

        return $this;
    }

    public function getPost(): ?Posts
    {
        return $this->post;
    }

    public function setPost(?Posts $post): static
    {
        $this->post = $post;

        return $this;
    }

    public function getOwner(): ?Users
    {
        return $this->owner;
    }

    public function setOwner(?Users $owner): static
    {
        $this->owner = $owner;

        return $this;
    }
}