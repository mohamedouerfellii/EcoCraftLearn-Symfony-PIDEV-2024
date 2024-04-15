<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Eventsevaluations
 *
 * @ORM\Table(name="eventsevaluations", indexes={@ORM\Index(name="idEvent", columns={"event"}), @ORM\Index(name="idUser", columns={"evaluator"})})
 * @ORM\Entity
 */
class Eventsevaluations
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
     * @var int
     *
     * @ORM\Column(name="rate", type="integer", nullable=false)
     */
    private $rate;

    /**
     * @var \Events
     *
     * @ORM\ManyToOne(targetEntity="Events")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="event", referencedColumnName="idEvent")
     * })
     */
    private $event;

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
