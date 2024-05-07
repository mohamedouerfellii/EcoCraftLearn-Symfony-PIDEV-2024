<?php

namespace App\Entity;

use App\Repository\CollectsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CollectsRepository::class)]
class Collects
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: "date")]
    
    public ?\DateTimeInterface $collectDate;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(message: "Il faut remplir ce champ")]
    private ?string $materialType = null;

    #[Assert\NotNull(message: "Il faut remplir ce champ")]
    #[ORM\Column(type: 'float')]
    private ?float $quantity = null;

    #[ORM\ManyToOne(inversedBy: 'collectss')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Collectspts $collectsptss = null;

    public function __construct()
    {
        $this->collectDate = new \DateTime(); // Initialiser la date collectDate avec la date actuelle lors de la création de l'entité
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCollectsptss(): ?Collectspts
    {
        return $this->collectsptss;
    }

    public function setCollectsptss(?collectspts $s): self
    {
        $this->collectsptss = $s;

        return $this;
    }

    public function getCollectDate(): ?\DateTimeInterface
    {
        return $this->collectDate;
    }

    public function setCollectDate(\DateTimeInterface $collectDate): self
    {
        $this->collectDate = $collectDate;

        return $this;
    }

    public function getMaterialType(): ?string
    {
        return $this->materialType;
    }

    public function setMaterialType(?string $materialType): self
    {
        $this->materialType = $materialType;

        return $this;
    }

    public function getQuantity(): ?float
    {
        return $this->quantity;
    }

    public function setQuantity(?float $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }
}
//******* */
