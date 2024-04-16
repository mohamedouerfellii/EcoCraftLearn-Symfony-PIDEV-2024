<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\EventsparticipationsRepository;

#[ORM\Entity(repositoryClass: EventsparticipationsRepository::class)]
class Eventsparticipations
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idparticipation = null;

    #[ORM\ManyToOne(targetEntity: "Events")]
    #[ORM\JoinColumn(name: "event", referencedColumnName: "idevent", onDelete: "CASCADE")]
    private ?Events $event;

    #[ORM\ManyToOne(targetEntity: "Users")]
    #[ORM\JoinColumn(name: "participant", referencedColumnName: "iduser", onDelete: "CASCADE")]
    private ?Users $participant;

    public function getIdparticipation(): ?int
    {
        return $this->idparticipation;
    }

    public function getEvent(): ?Events
    {
        return $this->event;
    }

    public function setEvent(?Events $event): static
    {
        $this->event = $event;

        return $this;
    }

    public function getParticipant(): ?Users
    {
        return $this->participant;
    }

    public function setParticipant(?Users $participant): static
    {
        $this->participant = $participant;

        return $this;
    }


}
