<?php

namespace App\Entity;

use App\Repository\QuizquestionsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: QuizquestionsRepository::class)]
class Quizquestions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idquestion = null;

    #[ORM\Column(length:65535)]
    #[Assert\NotBlank(message: "Your quiz must have a question !")]
    #[Assert\Length(min: 10, minMessage: "Question must contain at least 10 characters")]
    private ?string $question = null;

    #[ORM\Column(length:65535)]
    #[Assert\NotBlank(message: "Choice 1 cannot be blank")]
    #[Assert\NotEqualTo(propertyPath: "choice_2", message: "Each choice must be different")]
    #[Assert\NotEqualTo(propertyPath: "choice_3", message: "Each choice must be different")]
    #[Assert\NotEqualTo(propertyPath: "choice_4", message: "Each choice must be different")]
    private ?string $choice_1 = null;

    #[ORM\Column(length:65535)]
    #[Assert\NotBlank(message: "Choice 2 cannot be blank")]
    #[Assert\NotEqualTo(propertyPath: "choice_1", message: "Each choice must be different")]
    #[Assert\NotEqualTo(propertyPath: "choice_3", message: "Each choice must be different")]
    #[Assert\NotEqualTo(propertyPath: "choice_4", message: "Each choice must be different")]
    private ?string $choice_2 = null;

    #[ORM\Column(length:65535)]
    #[Assert\NotBlank(message: "Choice 3 cannot be blank")]
    #[Assert\NotEqualTo(propertyPath: "choice_2", message: "Each choice must be different")]
    #[Assert\NotEqualTo(propertyPath: "choice_1", message: "Each choice must be different")]
    #[Assert\NotEqualTo(propertyPath: "choice_4", message: "Each choice must be different")]
    private ?string $choice_3 = null;

    #[ORM\Column(length:65535)]
    #[Assert\NotBlank(message: "Choice 4 cannot be blank")]
    #[Assert\NotEqualTo(propertyPath: "choice_2", message: "Each choice must be different")]
    #[Assert\NotEqualTo(propertyPath: "choice_3", message: "Each choice must be different")]
    #[Assert\NotEqualTo(propertyPath: "choice_1", message: "Each choice must be different")]
    private ?string $choice_4 = null;

    #[ORM\Column(length:255)]
    private ?string $correct_choice = null;

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
        return $this->choice_1;
    }

    public function setChoice1(string $choice_1): static
    {
        $this->choice_1 = $choice_1;

        return $this;
    }

    public function getChoice2(): ?string
    {
        return $this->choice_2;
    }

    public function setChoice2(string $choice_2): static
    {
        $this->choice_2 = $choice_2;

        return $this;
    }

    public function getChoice3(): ?string
    {
        return $this->choice_3;
    }

    public function setChoice3(string $choice_3): static
    {
        $this->choice_3 = $choice_3;

        return $this;
    }

    public function getChoice4(): ?string
    {
        return $this->choice_4;
    }

    public function setChoice4(string $choice_4): static
    {
        $this->choice_4 = $choice_4;

        return $this;
    }

    public function getCorrectChoice(): ?string
    {
        return $this->correct_choice;
    }

    public function setCorrectChoice(string $correct_choice): static
    {
        $this->correct_choice = $correct_choice;

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
