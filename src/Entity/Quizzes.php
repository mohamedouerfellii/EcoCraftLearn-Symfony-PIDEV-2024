<?php

namespace App\Entity;

use App\Repository\QuizzesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuizzesRepository::class)]
class Quizzes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idquiz = 0;

    #[ORM\ManyToOne(targetEntity: "Sections")]
    #[ORM\JoinColumn(name: "section", referencedColumnName: "idsection", onDelete: "CASCADE")]
    private ?Sections $section;

    public function getIdquiz(): ?int
    {
        return $this->idquiz;
    }

    public function getSection(): ?Sections
    {
        return $this->section;
    }

    public function setSection(?Sections $section): static
    {
        $this->section = $section;

        return $this;
    }
}
