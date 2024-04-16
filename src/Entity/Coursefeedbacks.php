<?php

namespace App\Entity;

use App\Repository\CoursefeedbacksRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CoursefeedbacksRepository::class)]
class Coursefeedbacks
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idfeedback = null;

    #[ORM\Column(length:65535)]
    private ?string $content = null;

    #[ORM\Column]
    private ?int $rate = null;

    #[ORM\Column(type: "datetime", options: ["default" => "CURRENT_TIMESTAMP"])]
    private ?DateTime $posteddate = null;

    #[ORM\ManyToOne(targetEntity: "Courses")]
    #[ORM\JoinColumn(name: "course", referencedColumnName: "idCourse", onDelete: "CASCADE")]
    private ?Courses $course;

    #[ORM\ManyToOne(targetEntity: "Users")]
    #[ORM\JoinColumn(name: "owner", referencedColumnName: "idUser", onDelete: "CASCADE")]
    private ?Users $owner;

    public function getIdfeedback(): ?int
    {
        return $this->idfeedback;
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

    public function getRate(): ?int
    {
        return $this->rate;
    }

    public function setRate(int $rate): static
    {
        $this->rate = $rate;

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
