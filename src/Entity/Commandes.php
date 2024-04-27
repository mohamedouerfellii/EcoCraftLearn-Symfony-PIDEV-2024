<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commandes
 *
 * @ORM\Table(name="commandes", indexes={@ORM\Index(name="idUser", columns={"owner"}), @ORM\Index(name="idCart", columns={"cart"})})
 * @ORM\Entity
 */
class Commandes
{
    /**
     * @var int
     *
     * @ORM\Column(name="idCommande", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idcommande;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="commandeDate", type="date", nullable=false, options={"default"="current_timestamp()"})
     */
    private $commandedate = 'current_timestamp()';

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255, nullable=false)
     */
    private $city;

    /**
     * @var int
     *
     * @ORM\Column(name="phone", type="integer", nullable=false)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255, nullable=false)
     */
    private $status;

    /**
     * @var float
     *
     * @ORM\Column(name="latitude", type="float", precision=10, scale=0, nullable=false)
     */
    private $latitude;

    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="float", precision=10, scale=0, nullable=false)
     */
    private $longitude;

    /**
     * @var float
     *
     * @ORM\Column(name="total", type="float", precision=10, scale=0, nullable=false)
     */
    private $total;

    /**
     * @var \Users
     *
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="owner", referencedColumnName="idUser")
     * })
     */
    private $owner;

    /**
     * @var \Carts
     *
     * @ORM\ManyToOne(targetEntity="Carts")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cart", referencedColumnName="idCarts")
     * })
     */
    private $cart;


}
