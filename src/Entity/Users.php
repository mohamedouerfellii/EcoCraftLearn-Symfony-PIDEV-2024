<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "users")]
class Users
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "idUser", type: "integer", nullable: false)]
    private int $iduser;

    #[ORM\Column(name: "firstName", type: "string", length: 255, nullable: false)]
    private string $firstname;

    #[ORM\Column(name: "lastName", type: "string", length: 255, nullable: false)]
    private string $lastname;

    #[ORM\Column(name: "email", type: "string", length: 255, nullable: false)]
    private string $email;

    #[ORM\Column(name: "password", type: "string", length: 255, nullable: false)]
    private string $password;

    #[ORM\Column(name: "numTel", type: "integer", nullable: false)]
    private int $numtel;

    #[ORM\Column(name: "role", type: "string", length: 255, nullable: false)]
    private string $role;

    #[ORM\Column(name: "gender", type: "string", length: 255, nullable: false)]
    private string $gender;

    #[ORM\Column(name: "isActive", type: "boolean", nullable: false, options: ["default" => true])]
    private bool $isactive = true;

    #[ORM\Column(name: "nbrPtsCollects", type: "integer", nullable: false)]
    private int $nbrptscollects = 0;

    #[ORM\Column(name: "image", type: "string", length: 255, nullable: false)]
    private string $image;

    #[ORM\Column(name: "question", type: "string", length: 255, nullable: false)]
    private string $question;

    #[ORM\Column(name: "answer", type: "string", length: 255, nullable: false)]
    private string $answer;
public function __construct(
        string $firstname,
        string $lastname,
        string $email,
        string $password,
        int $numtel,
        string $role,
        string $gender,
        string $image,
        string $question,
        string $answer
    ) {
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->email = $email;
        $this->password = $password;
        $this->numtel = $numtel;
        $this->role = $role;
        $this->gender = $gender;
        $this->image = $image;
        $this->question = $question;
        $this->answer = $answer;
    }
    public function getIduser(): ?int
    {
        return $this->iduser;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }
    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function __toString(): string
    {
        return $this->firstname;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getNumtel(): ?int
    {
        return $this->numtel;
    }

    public function setNumtel(int $numtel): static
    {
        $this->numtel = $numtel;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): static
    {
        $this->gender = $gender;

        return $this;
    }

    public function isIsactive(): ?bool
    {
        return $this->isactive;
    }

    public function setIsactive(bool $isactive): static
    {
        $this->isactive = $isactive;

        return $this;
    }

    public function getNbrptscollects(): ?int
    {
        return $this->nbrptscollects;
    }

    public function setNbrptscollects(int $nbrptscollects): static
    {
        $this->nbrptscollects = $nbrptscollects;

        return $this;
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

    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    public function setAnswer(string $answer): static
    {
        $this->answer = $answer;

        return $this;
    }
}