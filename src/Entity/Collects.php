<?php

namespace App\Entity;

use App\Repository\CollectsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\CategoriecodepromoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;


#[ORM\Entity(repositoryClass: CollectsRepository::class)]
class Collects
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
   #[Assert\GreaterThan("now", message: "La date doit être postérieure à aujourd'hui")]
    public ?\DateTimeInterface $collectDate = null;


    
  

#[ORM\Column(length: 255)]
#[Assert\NotNull (message: "Il faut remplire ce chemp")]
    private ?string $materialType = null;

    #[Assert\NotNull (message: "Il faut remplire ce chemp")]
    #[ORM\Column(type: 'float')]
    private ?float $quantity = null;

    




#[ORM\ManyToOne(inversedBy: 'collectss')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Collectspts $collectsptss = null;



    


     




   
    
      

     

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