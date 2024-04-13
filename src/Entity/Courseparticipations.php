<?php

namespace App\Entity;

use App\Repository\CourseparticipationsRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CourseparticipationsRepository::class)]
class Courseparticipations
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idparticipation = null;

    #[ORM\Column(type: "datetime", options: ["default" => "CURRENT_TIMESTAMP"])]
    private ?DateTime $participationdate = null;

    #[ORM\Column]
    private ?int $sectiondone = 0;

    #[ORM\ManyToOne(targetEntity: "Courses")]
    #[ORM\JoinColumn(name: "course", referencedColumnName: "idcourse", onDelete: "CASCADE")]
    private ?Courses $course;

    #[ORM\ManyToOne(targetEntity: "Users")]
    #[ORM\JoinColumn(name: "participant", referencedColumnName: "iduser", onDelete: "CASCADE")]
    private ?Users $participant;

    public function getIdparticipation(): ?int
    {
        return $this->idparticipation;
    }

    public function getParticipationdate(): ?\DateTimeInterface
    {
        return $this->participationdate;
    }

    public function setParticipationdate(\DateTimeInterface $participationdate): static
    {
        $this->participationdate = $participationdate;

        return $this;
    }

    public function getSectiondone(): ?int
    {
        return $this->sectiondone;
    }

    public function setSectiondone(int $sectiondone): static
    {
        $this->sectiondone = $sectiondone;

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
    
    public function getParticipant(): ?Users
    {
        return $this->participant;
    }

    public function setParticipant(?Users $participant): static
    {
        $this->participant = $participant;

        return $this;
    }


}
