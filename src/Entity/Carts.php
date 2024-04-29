<?php

namespace App\Entity;

use App\Repository\CartsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CartsRepository::class)]
class Carts
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "idcarts", type: "integer")]
    private ?int $idcarts = null;
    
    #[ORM\Column(type: "float")]
    private ?float $totalprice = null;

    #[ORM\Column(type: "integer")]
    private ?int $isconfirmed = null;

    #[ORM\ManyToOne(targetEntity: "Users", inversedBy: "carts")]
    #[ORM\JoinColumn(name: "owner", referencedColumnName: "iduser", onDelete: "CASCADE")]
    private ?Users $owner=null;
    

    public function getIdcarts(): ?int
    {
        return $this->idcarts;
    }

    public function getTotalprice(): ?float
    {
        return $this->totalprice;
    }

    public function setTotalprice(float $totalprice): static
    {
        $this->totalprice = $totalprice;

        return $this;
    }

    public function isIsconfirmed(): ?bool
    {
        return $this->isconfirmed;
    }

    public function setIsconfirmed(bool $isconfirmed): static
    {
        $this->isconfirmed = $isconfirmed;

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

    public function getIsconfirmed(): ?int
    {
        return $this->isconfirmed;
    }

}
