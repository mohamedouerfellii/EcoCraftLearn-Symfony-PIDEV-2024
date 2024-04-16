<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Souscarts
 *
 * @ORM\Table(name="souscarts", indexes={@ORM\Index(name="fk_Cart", columns={"idCart"}), @ORM\Index(name="fk_product", columns={"id_product"})})
 * @ORM\Entity
 */
class Souscarts
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_SousCarts", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idSouscarts;

    /**
     * @var int
     *
     * @ORM\Column(name="QuantiteProduct", type="integer", nullable=false)
     */
    private $quantiteproduct;

    /**
     * @var \Carts
     *
     * @ORM\ManyToOne(targetEntity="Carts")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idCart", referencedColumnName="idCarts")
     * })
     */
    private $idcart;

    /**
     * @var \Products
     *
     * @ORM\ManyToOne(targetEntity="Products")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_product", referencedColumnName="idProduct")
     * })
     */
    private $idProduct;


}
