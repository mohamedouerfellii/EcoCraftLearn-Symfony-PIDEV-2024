<?php

namespace App\Entity;

use App\Repository\CollectsptsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CollectsptsRepository::class)]
class Collectspts
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

     #[ORM\Column(type: 'float', nullable: true)]
    private ?float $capacity = null;

   


    
   
    

    #[ORM\Column(length: 255)]
    #[Assert\NotNull (message: "Il faut remplire ce chemp")]
    private ?string $adresse = null;

    

    

    #[ORM\OneToMany(mappedBy: 'collectsptss', targetEntity: Collects::class, orphanRemoval: true)]
    private Collection $collectss;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull (message: "Il faut remplire ce chemp")]
    private ?string $Name = null;

    
    
    #[ORM\Column(length: 255)]
   private ?string $image = null;

    public function __construct()
    {
        $this->collectss = new ArrayCollection();
    }
    

    public function getId(): ?int
    {
        return $this->id;
    }

   
    
    

    

    

   

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }



    
    /**
     * @return Collection<int, Collects>
     */
    public function getCollectss(): Collection
    {
        return $this->collectss;
    }

    public function addCCollectsontrat( $collects): self
    {
        if (!$this->collectss->contains($collects)) {
            $this->collectss->add($collects);
            $collects->setCollectsptss($this);
        }

        return $this;
    }

    public function removeCCollectsontrat( $collects): self
    {
        if ($this->collectss->removeElement($collects)) {
            // set the owning side to null (unless already changed)
            if ($collects->getCollectsptss() === $this) {
                $collects->setCollectsptss(null);
            }
        }

        return $this;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }
    

     public function getCapacity(): ?float
    {
        return $this->capacity;
    }

    public function setCapacity(?float $capacity): self
    {
        $this->capacity = $capacity;

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
    
}
//******* */
