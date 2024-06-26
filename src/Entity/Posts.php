<?php

namespace App\Entity;

use App\Repository\PostsRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PostsRepository::class)]
class Posts
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "idPost", type: "integer", nullable: false)]

    private ?int $idpost = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'you need to write a post')]
    private ?string $content = null;

    #[ORM\Column]

    private ?string $attachment = null;

    #[ORM\Column(type: "datetime", options: ["default" => "CURRENT_TIMESTAMP"])]
    private ?DateTime $posteddate = null;

    #[ORM\ManyToOne(targetEntity: Users::class)]
    #[ORM\JoinColumn(name: "owner", referencedColumnName: "iduser", onDelete: "CASCADE")]
    private ?Users $owner = null;
   

    #[ORM\OneToMany(mappedBy: "post", targetEntity: Comments::class, cascade: ["persist", "remove"])]
    private $comments;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    public function getComments(): \Doctrine\Common\Collections\Collection
    {
        return $this->comments;
    }
    public function getIdpost(): ?int
    {
        return $this->idpost;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function getAttachment(): ?string
    {
        return $this->attachment;
    }

    public function setAttachment(?string $attachment): self
    {
        $this->attachment = $attachment;
        return $this;
    }

    public function getPosteddate(): ?\DateTimeInterface
    {
        return $this->posteddate;
    }

    public function setPosteddate(\DateTimeInterface $posteddate): self
    {
        $this->posteddate = $posteddate;
        return $this;
    }

    public function getOwner(): ?Users
    {
        return $this->owner;
    }

    public function setOwner(?Users $owner): self
    {
        $this->owner = $owner;
        return $this;
    }
}