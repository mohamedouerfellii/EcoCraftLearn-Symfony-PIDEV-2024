<?php

namespace App\Entity;

use App\Repository\CoursesRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CoursesRepository::class)]
class Courses
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idcourse = null;

    #[ORM\Column(length:255)]
    private ?string $image = null;

    #[ORM\Column(length:255)]
    private ?string $title = null;

    #[ORM\Column(length:65535)]
    private ?string $description = null;

    #[ORM\Column(length:255)]
    private ?string $duration = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column]
    private ?int $nbrsection = 0;

    #[ORM\Column(type: "datetime", options: ["default" => "CURRENT_TIMESTAMP"])]
    private ?DateTime $posteddate = null;

    #[ORM\Column]
    private ?int $nbrregistred = 0;

    #[ORM\Column]
    private ?float $rate = 0;

    #[ORM\Column]
    private ?int $nbrpersonrated = 0;

    #[ORM\ManyToOne(targetEntity: "Users")]
    #[ORM\JoinColumn(name: "tutor", referencedColumnName: "idUser", onDelete: "CASCADE")]
    private ?Users $tutor;

    public function getIdcourse(): ?int
    {
        return $this->idcourse;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
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

    public function getDuration(): ?string
    {
        return $this->duration;
    }

    public function setDuration(string $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getNbrsection(): ?int
    {
        return $this->nbrsection;
    }

    public function setNbrsection(int $nbrsection): static
    {
        $this->nbrsection = $nbrsection;

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

    public function getNbrregistred(): ?int
    {
        return $this->nbrregistred;
    }

    public function setNbrregistred(int $nbrregistred): static
    {
        $this->nbrregistred = $nbrregistred;

        return $this;
    }

    public function getRate(): ?float
    {
        return $this->rate;
    }

    public function setRate(float $rate): static
    {
        $this->rate = $rate;

        return $this;
    }

    public function getNbrpersonrated(): ?int
    {
        return $this->nbrpersonrated;
    }

    public function setNbrpersonrated(int $nbrpersonrated): static
    {
        $this->nbrpersonrated = $nbrpersonrated;

        return $this;
    }

    public function getTutor(): ?Users
    {
        return $this->tutor;
    }

    public function setTutor(?Users $tutor): static
    {
        $this->tutor = $tutor;

        return $this;
    }
}
