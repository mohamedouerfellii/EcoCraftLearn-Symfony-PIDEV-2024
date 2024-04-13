<?php

namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\CommandesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandesRepository::class)]
class Commandes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "idcommande", type: "integer")]
    private $idcommande;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $commandedate;
    public function __construct()
    {
        $this->commandedate = new \DateTime(); 
    }
    #[ORM\Column(length:255)]
    #[Assert\NotBlank(message: "Your order must have a email !")]
    private ?string $email = null;

    #[ORM\Column(length:255)]
    #[Assert\NotBlank(message: "Your order must have a city !")]
    private ?string $city = null;

 
    #[ORM\Column]
    private ?float $phone = null;

    #[ORM\Column(length: 255, options: ["default" => "inProgress"])]
    private ?string $status = "inProgress";

    #[ORM\Column]
    private ?float $latitude=0;

    #[ORM\Column]
    private ?float $longitude=0;

    #[ORM\ManyToOne(targetEntity: "Users")]
    #[ORM\JoinColumn(name: "owner", referencedColumnName: "iduser", onDelete: "CASCADE")]
    private ?Users $owner=null;


    #[ORM\ManyToOne(targetEntity: "Carts")]
    #[ORM\JoinColumn(name: "cart", referencedColumnName: "idcarts", onDelete: "CASCADE")]
    private ?Carts $cart=null;

    #[ORM\Column]
    private ?float $total=null;

    public function getOwner(): ?Users
    {
        return $this->owner;
    }

    public function setOwner(?Users $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    public function getCart(): ?Carts
    {
        return $this->cart;
    }

    public function setCart(?Carts $cart): static
    {
        $this->cart = $cart;

        return $this;
    }





    public function getIdcommande(): ?int
    {
        return $this->idcommande;
    }

    public function getCommandedate()
    {
        return $this->commandedate;
    }

    public function setCommandedate($commandedate): static
    {
        $this->commandedate = $commandedate;

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

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getPhone(): ?float
    {
        return $this->phone;
    }

    public function setPhone(float $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): static
    {
        $this->total = $total;

        return $this;
    }




}
