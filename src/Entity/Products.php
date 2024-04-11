<?php

namespace App\Entity;
use App\Repository\ProductsRepository;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductsRepository::class)]
class Products
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer", name: "idproduct")]

    private ?int $idproduct=null;

    #[ORM\Column(length: 255)]
    private ?string $name=null;

    #[ORM\Column(length: 255)]
    private ?string $description=null;

    #[ORM\Column(length: 255)]
    private ?string $image=null;


    #[ORM\Column]
    private ?float $price=null;

    

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $adddate;

    #[ORM\Column]
    private ?int $quantite=null;

    public function __construct()
    {
        $this->adddate = new \DateTime(); 
    }


    #[ORM\ManyToOne(targetEntity: "Users")]
    #[ORM\JoinColumn(name: "owner", referencedColumnName: "iduser", onDelete: "CASCADE")]

    private ?Users $owner=null;

    public function getIdproduct(): ?int
    {
        return $this->idproduct;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getAdddate(): ?\DateTimeInterface
    {
        return $this->adddate;
    }

    public function setAdddate(\DateTimeInterface $adddate): static
    {
        $this->adddate = $adddate;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(?int $quantite): static
    {
        $this->quantite = $quantite;

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