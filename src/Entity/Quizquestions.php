<?php

namespace App\Entity;

use App\Repository\QuizquestionsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuizquestionsRepository::class)]
class Quizquestions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idquestion = null;

    #[ORM\Column(length:65535)]
    private ?string $question = null;

    #[ORM\Column(length:65535)]
    private ?string $choice1 = null;

    #[ORM\Column(length:65535)]
    private ?string $choice2 = null;

    #[ORM\Column(length:65535)]
    private ?string $choice3 = null;

    #[ORM\Column(length:65535)]
    private ?string $choice4 = null;

    #[ORM\Column(length:255)]
    private ?string $correctChoice = null;

    #[ORM\ManyToOne(targetEntity: "Quizzes")]
    #[ORM\JoinColumn(name: "quiz", referencedColumnName: "idquiz", onDelete: "CASCADE")]
    private $quiz;

    public function getIdquestion(): ?int
    {
        return $this->idquestion;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): static
    {
        $this->question = $question;

        return $this;
    }

    public function getChoice1(): ?string
    {
        return $this->choice1;
    }

    public function setChoice1(string $choice1): static
    {
        $this->choice1 = $choice1;

        return $this;
    }

    public function getChoice2(): ?string
    {
        return $this->choice2;
    }

    public function setChoice2(string $choice2): static
    {
        $this->choice2 = $choice2;

        return $this;
    }

    public function getChoice3(): ?string
    {
        return $this->choice3;
    }

    public function setChoice3(string $choice3): static
    {
        $this->choice3 = $choice3;

        return $this;
    }

    public function getChoice4(): ?string
    {
        return $this->choice4;
    }

    public function setChoice4(string $choice4): static
    {
        $this->choice4 = $choice4;

        return $this;
    }

    public function getCorrectChoice(): ?string
    {
        return $this->correctChoice;
    }

    public function setCorrectChoice(string $correctChoice): static
    {
        $this->correctChoice = $correctChoice;

        return $this;
    }

    public function getQuiz(): ?Quizzes
    {
        return $this->quiz;
    }

    public function setQuiz(?Quizzes $quiz): static
    {
        $this->quiz = $quiz;

        return $this;
    }
}
