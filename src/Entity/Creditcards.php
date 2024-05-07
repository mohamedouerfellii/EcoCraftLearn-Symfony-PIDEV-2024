<?php

namespace App\Entity;

use App\Repository\CreditcardsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CreditcardsRepository::class)]
class Creditcards
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idcard = null;

    #[ORM\Column(length:255)]
    private ?string $cardnumber = null;

    #[ORM\Column(length:255)]
    private ?string $ccv = null;

    #[ORM\Column(length:255)]
    private ?string $expiredate = null;

    #[ORM\ManyToOne(targetEntity: "Users")]
    #[ORM\JoinColumn(name: "owner", referencedColumnName: "iduser", onDelete: "CASCADE")]
    private ?Users $owner;

    public function getIdcard(): ?int
    {
        return $this->idcard;
    }

    public function getCardnumber(): ?string
    {
        return $this->cardnumber;
    }

    public function setCardnumber(string $cardnumber): static
    {
        $this->cardnumber = $cardnumber;

        return $this;
    }

    public function getCcv(): ?string
    {
        return $this->ccv;
    }

    public function setCcv(string $ccv): static
    {
        $this->ccv = $ccv;

        return $this;
    }

    public function getExpiredate(): ?string
    {
        return $this->expiredate;
    }

    public function setExpiredate(string $expiredate): static
    {
        $this->expiredate = $expiredate;

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
