<?php

namespace App\Entity;

use App\Repository\QuizanswersRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuizanswersRepository::class)]
class Quizanswers
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idanswer = null;

    #[ORM\Column]
    private ?float $result = null;

    #[ORM\Column(type: "datetime", options: ["default" => "CURRENT_TIMESTAMP"])]
    private ?DateTime $answerdate = null;

    #[ORM\ManyToOne(targetEntity: "Quizzes")]
    #[ORM\JoinColumn(name: "quizz", referencedColumnName: "idQuiz", onDelete: "CASCADE")]
    private ?Quizzes $quizz;

    #[ORM\ManyToOne(targetEntity: "Users")]
    #[ORM\JoinColumn(name: "student", referencedColumnName: "idUser", onDelete: "CASCADE")]
    private ?Users $student;

    public function getIdanswer(): ?int
    {
        return $this->idanswer;
    }

    public function getResult(): ?float
    {
        return $this->result;
    }

    public function setResult(float $result): static
    {
        $this->result = $result;

        return $this;
    }

    public function getAnswerdate(): ?\DateTimeInterface
    {
        return $this->answerdate;
    }

    public function setAnswerdate(\DateTimeInterface $answerdate): static
    {
        $this->answerdate = $answerdate;

        return $this;
    }

    public function getQuizz(): ?Quizzes
    {
        return $this->quizz;
    }

    public function setQuizz(?Quizzes $quizz): static
    {
        $this->quizz = $quizz;

        return $this;
    }

    public function getStudent(): ?Users
    {
        return $this->student;
    }

    public function setStudent(?Users $student): static
    {
        $this->student = $student;

        return $this;
    }

}
