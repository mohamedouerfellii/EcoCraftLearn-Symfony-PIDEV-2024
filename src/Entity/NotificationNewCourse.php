<?php

namespace App\Entity;

use App\Repository\NotificationNewCourseRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Entity(repositoryClass: NotificationNewCourseRepository::class)]
class NotificationNewCourse implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idnotification = null;

    #[ORM\Column(length:255)]
    private ?string $title = null;

    #[ORM\Column]
    private ?bool $isChecked = null;

    #[ORM\Column(type: "datetime", options: ["default" => "CURRENT_TIMESTAMP"])]
    private ?DateTime $date = null;

    #[ORM\ManyToOne(targetEntity: "Users")]
    #[ORM\JoinColumn(name: "tutor_notified", referencedColumnName: "iduser", onDelete: "CASCADE")]
    private ?Users $tutor_notified;

    #[ORM\ManyToOne(targetEntity: "Courses")]
    #[ORM\JoinColumn(name: "course_notified", referencedColumnName: "idcourse", onDelete: "CASCADE")]
    private ?Courses $course_notified;

    public function jsonSerialize()
    {
        return array(
            'idnotification' => $this->idnotification,
            'title'=> $this->title,
            'isChecked'=> $this->isChecked,
            'date'=> $this->date->format('Y-m-d'),
            'tutor' => $this->tutor_notified->getIduser()
        );
    }

    public function getIdnotification(): ?int
    {
        return $this->idnotification;
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

    public function isIsChecked(): ?bool
    {
        return $this->isChecked;
    }

    public function setIsChecked(bool $isChecked): static
    {
        $this->isChecked = $isChecked;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getTutorNotified(): ?Users
    {
        return $this->tutor_notified;
    }

    public function setTutorNotified(?Users $tutor_notified): static
    {
        $this->tutor_notified = $tutor_notified;

        return $this;
    }

    public function getcourse_notified(): ?Courses
    {
        return $this->course_notified;
    }

    public function setCourseNotified(?Courses $course_notified): static
    {
        $this->course_notified = $course_notified;

        return $this;
    }
}
