<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Eventsparticipations
 *
 * @ORM\Table(name="eventsparticipations", indexes={@ORM\Index(name="idEvent", columns={"event"}), @ORM\Index(name="idUser", columns={"participant"})})
 * @ORM\Entity
 */
class Eventsparticipations
{
    /**
     * @var int
     *
     * @ORM\Column(name="idParticipation", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idparticipation;

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
     *   @ORM\JoinColumn(name="participant", referencedColumnName="idUser")
     * })
     */
    private $participant;


}
