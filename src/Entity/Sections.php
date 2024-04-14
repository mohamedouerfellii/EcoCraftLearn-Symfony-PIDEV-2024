<?php

namespace App\Entity;

use App\Repository\SectionsRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SectionsRepository::class)]
class Sections
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idsection = null;

    #[ORM\Column(length:255)]
    #[Assert\NotBlank(message: "Your section must have a title !")]
    private ?string $title = null;

    #[ORM\Column(length:65535)]
    #[Assert\NotBlank(message: "Your section must have a description !")]
    #[Assert\Length(min: 20, minMessage: "Description must contain at least 20 characters")]
    private ?string $description = null;

    #[ORM\Column(length:255)]
    private ?string $attachment = null;

    #[ORM\Column(type: "datetime", options: ["default" => "CURRENT_TIMESTAMP"])]
    private ?DateTime $posteddate = null;

    #[ORM\ManyToOne(targetEntity: "Courses")]
    #[ORM\JoinColumn(name: "course", referencedColumnName: "idcourse", onDelete: "CASCADE")]
    private ?Courses $course;

    public function getIdsection(): ?int
    {
        return $this->idsection;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

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

    public function getPosteddate(): ?\DateTimeInterface
    {
        return $this->posteddate;
    }

    public function setPosteddate(\DateTimeInterface $posteddate): static
    {
        $this->posteddate = $posteddate;

        return $this;
    }

    public function getCourse(): ?Courses
    {
        return $this->course;
    }

    public function setCourse(?Courses $course): static
    {
        $this->course = $course;

        return $this;
    }

}
