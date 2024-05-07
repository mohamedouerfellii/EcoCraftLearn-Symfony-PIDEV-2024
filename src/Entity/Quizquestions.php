<?php

namespace App\Entity;

use App\Repository\QuizquestionsRepository;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: QuizquestionsRepository::class)]
class Quizquestions implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idquestion = null;

    #[ORM\Column(length:65535)]
    #[Assert\NotBlank(message:"Your quiz must have a question")]
    #[Assert\Length(min: 10, minMessage:"The question must be at least 10 characters long")]
    private ?string $question = null;

    #[ORM\Column(name: "choice_1", length: 65535)]
    #[Assert\NotBlank(message:"Choice 1 is required")]
    #[Assert\NotEqualTo(propertyPath: "choice4", message: "Each choice must be different.")]
    #[Assert\NotEqualTo(propertyPath: "choice2", message: "Each choice must be different.")]
    #[Assert\NotEqualTo(propertyPath: "choice3", message: "Each choice must be different.")]
    private ?string $choice1 = null;

    #[ORM\Column(name: "choice_2", length: 65535)]
    #[Assert\NotBlank(message:"Choice 2 is required")]
    #[Assert\NotEqualTo(propertyPath: "choice1", message: "Each choice must be different.")]
    #[Assert\NotEqualTo(propertyPath: "choice3", message: "Each choice must be different.")]
    #[Assert\NotEqualTo(propertyPath: "choice4", message: "Each choice must be different.")]
    private ?string $choice2 = null;

    #[ORM\Column(name: "choice_3", length: 65535)]
    #[Assert\NotBlank(message:"Choice 3 is required")]
    #[Assert\NotEqualTo(propertyPath: "choice1", message: "Each choice must be different.")]
    #[Assert\NotEqualTo(propertyPath: "choice2", message: "Each choice must be different.")]
    #[Assert\NotEqualTo(propertyPath: "choice4", message: "Each choice must be different.")]
    private ?string $choice3 = null;

    #[ORM\Column(name: "choice_4", length: 65535)]
    #[Assert\NotBlank(message:"Choice 4 is required")]
    #[Assert\NotEqualTo(propertyPath: "choice1", message: "Each choice must be different.")]
    #[Assert\NotEqualTo(propertyPath: "choice2", message: "Each choice must be different.")]
    #[Assert\NotEqualTo(propertyPath: "choice3", message: "Each choice must be different.")]
    private ?string $choice4 = null;

    #[ORM\Column(name: "correct_choice", length: 255)]
    private ?string $correctChoice = null;


    #[ORM\ManyToOne(targetEntity: "Quizzes")]
    #[ORM\JoinColumn(name: "quiz", referencedColumnName: "idquiz", onDelete: "CASCADE")]
    private $quiz;

    public function jsonSerialize()
    {
        return array(
            'question' => $this->question,
            'answer'=> $this->correctChoice,
            'options' => [
                $this->choice1,
                $this->choice2,
                $this->choice3,
                $this->choice4
            ],
            'idCourse' => $this->quiz->getSection()->getCourse()->getIdcourse(),
            'idQuiz' => $this->quiz->getIdquiz()
        );
    }

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
