<?php

namespace App\Entity;
use App\Repository\PostsRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PostsRepository::class)]
class Posts
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    
    private ?int $idpost=null;

    #[ORM\Column]
    #[Assert\NotBlank(message:'you need to write a post')]
    private ?string $content=null;

    #[ORM\Column]

    private ?string $attachment = null;

    #[ORM\Column(type: "datetime", options: ["default" => "CURRENT_TIMESTAMP"])]
    private ?DateTime $posteddate =null;

    #[ORM\ManyToOne(targetEntity: Users::class)]
    #[ORM\JoinColumn(name: "owner", referencedColumnName: "idUser", onDelete: "CASCADE")]
    
    private ?Users $owner=null;

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