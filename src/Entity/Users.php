<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UsersRepository;
use JsonSerializable;
use Scheb\TwoFactorBundle\Model\Email\TwoFactorInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]

class Users implements PasswordAuthenticatedUserInterface,UserInterface,TwoFactorInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $iduser = null;

    #[ORM\Column(length:255)]
    private ?string $firstname = null;

    #[ORM\Column(length:255)]
    private ?string $lastname = null;

    #[ORM\Column(length:255)]
    private ?string $email = null;

    #[ORM\Column(length:255)]
    private ?string $password = null;

    #[ORM\Column]
    private ?int $numtel = null;

    #[ORM\Column(length:255)]
    private ?string $role = null;

    #[ORM\Column(length:255)]
    private ?string $gender = null;

    #[ORM\Column]
    private ?bool $isactive = true;

    #[ORM\Column]
    private ?int $nbrptscollects = 0;

    #[ORM\Column(length:255)]
    private ?string $image = null;

    #[ORM\Column(length:255)]
    private ?string $question = null;

    #[ORM\Column(length:255)]
    private ?string $answer = null;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;
    #[ORM\Column(type: 'string', nullable: true)]
    private  $authCode;

    #[ORM\Column(length:255)]
    private ?string $reset_token;

    public function getIduser(): ?int
    {
        return $this->iduser;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

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

    public function getUserIdentifier(){
        return (string)$this->email;
    }
    public function getRoles(){
        $r = strtoupper($this->role);
        return ["ROLE_".$r];    
    }
    public function getSalt(){
        return null;
    }
    public function eraseCredentials(){
        return null;
    }
    public function getUsername(){
        return $this->email;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }
     
    public function getResetToken()
    {
        return $this->reset_token;
    }

   
    public function setResetToken($reset_token): void
    {
        $this->reset_token = $reset_token;
    }


    
    public function isEmailAuthEnabled(): bool
    {
        return true; // This can be a persisted field to switch email code authentication on/off
    }

    public function getEmailAuthRecipient(): string
    {
        return $this->email;
    }

    public function getEmailAuthCode(): string
    {
        if (null === $this->authCode) {
           return null;
        }

        return $this->authCode;
    }

    public function setEmailAuthCode(string $authCode): void
    {
        $this->authCode = $authCode;
    }






}
