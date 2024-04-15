<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\EventsRepository;
use DateTimeInterface;


use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: EventsRepository::class)]



class Events
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "idevent", type: "integer")]
    private ?int $idevent = null;

    #[ORM\Column(length:255)]
    #[Assert\NotBlank(message: "Your event must have a title !")]
    private ?string $title = null;

    #[ORM\Column(length:65535)]
    #[Assert\NotBlank(message: "Your event must have a description !")]
    private ?string $description = null;

    #[ORM\Column(type: "datetime")]
    #[Assert\NotBlank(message: "Your event must have a start date !")]
    private ?\DateTimeInterface $startdate = null;

    #[ORM\Column(type: "datetime")]
    #[Assert\NotBlank(message: "Your event must have an end date !")]
    private ?\DateTimeInterface $enddate = null;

    
    #[ORM\Column(length:255)]
    private ?string $attachment = null;
  

    #[ORM\Column(length:65535)]
    #[Assert\NotBlank(message: "Your event must have a type !")]
    private ?string $eventtype = null;

    #[ORM\Column(length:65535)]
    #[Assert\NotBlank(message: "Your event must have a place !")]
    private ?string $place = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Your event must have a Number !")]
    private ?float $placenbr = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Your event must have a price !")]
    private ?float $price = null;


    #[ORM\ManyToOne(targetEntity: "Users")]
    #[ORM\JoinColumn(name: "owner", referencedColumnName: "iduser", onDelete: "CASCADE")]
    private ?Users $owner;

    public function getIdevent(): ?int
    {
        return $this->idevent;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getStartdate(): ?\DateTimeInterface
    {
        return $this->startdate;
    }

    public function setStartdate(\DateTimeInterface $startdate): static
    {
        $this->startdate = $startdate;

        return $this;
    }

    public function getEnddate(): ?\DateTimeInterface
    {
        return $this->enddate;
    }

    public function setEnddate(\DateTimeInterface $enddate): static
    {
        $this->enddate = $enddate;

        return $this;
    }

    public function getAttachment(): ?string
    {
        return $this->attachment;
    }

    public function setAttachment(string $attachment): static
    {
        $this->attachment = $attachment;

        return $this;
    }

    public function getEventtype(): ?string
    {
        return $this->eventtype;
    }

    public function setEventtype(string $eventtype): static
    {
        $this->eventtype = $eventtype;

        return $this;
    }

    public function getPlace(): ?string
    {
        return $this->place;
    }

    public function setPlace(string $place): static
    {
        $this->place = $place;

        return $this;
    }

    public function getPlacenbr(): ?float
    {
        return $this->placenbr;
    }

    public function setPlacenbr(float $placenbr): static
    {
        $this->placenbr = $placenbr;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getOwner(): ?Users
    {
        return $this->owner;
    }

    public function setOwner(?Users $owner): static
    {
        $this->owner = $owner;

        return $this;
    }



    

}
