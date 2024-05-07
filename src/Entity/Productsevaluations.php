<?php

namespace App\Entity;

use App\Repository\ProductsevaluationsRepository;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: ProductsevaluationsRepository::class)]
class Productsevaluations
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer", name: "idevaluation")]
    private ?int $idevaluation=null;
  

  
    #[ORM\Column]
    #[Assert\NotBlank(message: "Your rate must have a ratestar !")]
    private ?float $rate=0;

    
    #[ORM\Column(length:65535)]
    #[Assert\NotBlank(message: "Your rate must have a commentaire !")]
    #[Assert\Length(min: 20, minMessage: "commentaire must contain at least 20 characters")]
    private ?string $commentaire = null;


    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $evaluationdate;

    public function __construct()
    {
        $this->evaluationdate = new \DateTime(); 
    }
    #[ORM\ManyToOne(targetEntity: "Products")]
    #[ORM\JoinColumn(name: "product", referencedColumnName: "idproduct")]
    private ?Products $product=null;

    #[ORM\ManyToOne(targetEntity: "Users")]
    #[ORM\JoinColumn(name: "evaluator", referencedColumnName: "iduser", onDelete: "CASCADE")]
    private ?Users $evaluator=null;

    public function getIdevaluation(): ?int
    {
        return $this->idevaluation;
    }

    public function getRate(): ?float
    {
        return $this->rate;
    }

    public function setRate(float $rate): static
    {
        $this->rate = $rate;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(string $commentaire): static
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getEvaluationdate(): ?\DateTimeInterface
    {
        return $this->evaluationdate;
    }

    public function setEvaluationdate(\DateTimeInterface $evaluationdate): static
    {
        $this->evaluationdate = $evaluationdate;

        return $this;
    }

    public function getProduct(): ?Products
    {
        return $this->product;
    }

    public function setProduct(?Products $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function getEvaluator(): ?Users
    {
        return $this->evaluator;
    }

    public function setEvaluator(?Users $evaluator): static
    {
        $this->evaluator = $evaluator;

        return $this;
    }

}
