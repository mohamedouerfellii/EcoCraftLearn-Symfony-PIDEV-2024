<?php

namespace App\Entity;
use App\Entity\Carts;
use App\Entity\Products;
use App\Repository\SouscartsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SouscartsRepository::class)]
class Souscarts
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idSouscarts = null;

    #[ORM\Column]
    private ?int $quantiteproduct = null;

    #[ORM\ManyToOne(targetEntity: "Carts", inversedBy: "sousCarts")]
    #[ORM\JoinColumn(name: "idCart", referencedColumnName: "idcarts")]
    private ?Carts $cart;

    #[ORM\ManyToOne(targetEntity: "Products")]
    #[ORM\JoinColumn(name: "id_product", referencedColumnName: "idproduct")]
    private ?Products $product;
    

    public function getIdSouscarts(): ?int
    {
        return $this->idSouscarts;
    }

    public function getQuantiteproduct(): ?int
    {
        return $this->quantiteproduct;
    }

    public function setQuantiteproduct(int $quantiteproduct): static
    {
        $this->quantiteproduct = $quantiteproduct;

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

    public function getProduct(): ?Products
    {
        return $this->product;
    }

    public function setProduct(?Products $product): static
    {
        $this->product = $product;

        return $this;
    }






 



}
