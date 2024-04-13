<?php

namespace App\Entity;
use App\Entity\Carts;
use App\Entity\Products;
use App\Entity\Users;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\CartsRepository;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
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

    public function isIsconfirmed(): ?int
    {
        return $this->isconfirmed;
    }

    public function setIsconfirmed(int $isconfirmed): static
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

    private $sousCarts;

    public function __construct()
    {
        $this->sousCarts = new ArrayCollection();
    }
    public function addSousCart(Souscarts $sousCart): self
    {
        if (!$this->sousCarts->contains($sousCart)) {
            $this->sousCarts[] = $sousCart;
            $sousCart->setCart($this);
        }

        return $this;
    }

    public function getSousCarts(): Collection
    {
        return $this->sousCarts ?: new ArrayCollection();
    }

    public function getIsconfirmed(): ?int
    {
        return $this->isconfirmed;
    }
    public function updateTotalPrice(): void
    {
        $totalPrice = 0;
    
        foreach ($this->getSousCarts() as $sousCart) {
            $totalPrice += $sousCart->getProduct()->getPrice() * $sousCart->getQuantiteproduct();
        }
    
        $this->totalprice = $totalPrice;
    }
    




}
