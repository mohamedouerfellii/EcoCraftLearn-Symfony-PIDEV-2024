<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Productsevaluations
 *
 * @ORM\Table(name="productsevaluations", indexes={@ORM\Index(name="idProduct", columns={"product"}), @ORM\Index(name="idUser", columns={"evaluator"})})
 * @ORM\Entity
 */
class Productsevaluations
{
    /**
     * @var int
     *
     * @ORM\Column(name="idEvaluation", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idevaluation;

    /**
     * @var float
     *
     * @ORM\Column(name="rate", type="float", precision=10, scale=0, nullable=false)
     */
    private $rate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="evaluationDate", type="date", nullable=false, options={"default"="current_timestamp()"})
     */
    private $evaluationdate = 'current_timestamp()';

    /**
     * @var \Products
     *
     * @ORM\ManyToOne(targetEntity="Products")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product", referencedColumnName="idProduct")
     * })
     */
    private $product;

    /**
     * @var \Users
     *
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="evaluator", referencedColumnName="idUser")
     * })
     */
    private $evaluator;


}
