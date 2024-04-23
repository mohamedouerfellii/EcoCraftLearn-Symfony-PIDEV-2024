<?php

namespace App\Entity;

use App\Repository\CollectsRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CollectsRepository::class)]
class Collects
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idcollect = null;

    #[ORM\Column(length:255)]
    private ?string $materialtype = null;

    #[ORM\Column]
    private ?float $quantity = null;

    #[ORM\Column(type: "datetime", options: ["default" => "CURRENT_TIMESTAMP"])]
    private ?DateTime $collectsdate = null;

    #[ORM\ManyToOne(targetEntity: "Users")]
    #[ORM\JoinColumn(name: "collector", referencedColumnName: "iduser", onDelete: "CASCADE")]
    private ?Users $collector;

    #[ORM\ManyToOne(targetEntity: "Collectspts")]
    #[ORM\JoinColumn(name: "collectsPts", referencedColumnName: "idcollectspts", onDelete: "CASCADE")]
    private ?Collectspts $collectspts;

    public function getIdcollect(): ?int
    {
        return $this->idcollect;
    }

    public function getMaterialtype(): ?string
    {
        return $this->materialtype;
    }

    public function setMaterialtype(string $materialtype): static
    {
        $this->materialtype = $materialtype;

        return $this;
    }

    public function getQuantity(): ?float
    {
        return $this->quantity;
    }

    public function setQuantity(float $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getCollectsdate(): ?\DateTimeInterface
    {
        return $this->collectsdate;
    }

    public function setCollectsdate(\DateTimeInterface $collectsdate): static
    {
        $this->collectsdate = $collectsdate;

        return $this;
    }

    public function getCollector(): ?Users
    {
        return $this->collector;
    }

    public function setCollector(?Users $collector): static
    {
        $this->collector = $collector;

        return $this;
    }

    public function getCollectspts(): ?Collectspts
    {
        return $this->collectspts;
    }

    public function setCollectspts(?Collectspts $collectspts): static
    {
        $this->collectspts = $collectspts;

        return $this;
    }


}
