<?php

namespace App\Entity\DTO;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;

class LectureDTO
{
    private ?int $id = null;

    private ?int $event_id = null;

    private ?string $lecturer = null;

    private ?string $location = null;

    private ?int $attendees_quantity = null;

    private ?string $subtitle = null;

    private ?string $description = null;

    private ?\DateTimeInterface $start_date = null;

    private ?\DateTimeInterface $end_date = null;

    private ?string $lecturer_image = null;

    private ?\DateTimeImmutable $created_at = null;

    private ?\DateTimeInterface $updated_at = null;

    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getEventId(): ?int
    {
        return $this->event_id;
    }

    public function setEventId(int $event_id): self
    {
        $this->event_id = $event_id;
        return $this;
    }

    public function getLecturer(): ?string
    {
        return $this->lecturer;
    }

    public function setLecturer(string $lecturer): self
    {
        $this->lecturer = $lecturer;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getAttendeesQuantity(): ?int
    {
        return $this->attendees_quantity;
    }

    public function setAttendeesQuantity(int $attendees_quantity): self
    {
        $this->attendees_quantity = $attendees_quantity;

        return $this;
    }

    public function getSubtitle(): ?string
    {
        return $this->subtitle;
    }

    public function setSubtitle(string $subtitle): self
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->start_date;
    }

    public function setStartDate(\DateTimeInterface $start_date): self
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->end_date;
    }

    public function setEndDate(\DateTimeInterface $end_date): self
    {
        $this->end_date = $end_date;

        return $this;
    }

    public function getLecturerImage(): ?string
    {
        return $this->lecturer_image;
    }

    public function setLecturerImage(?string $lecturer_image): self
    {
        $this->lecturer_image = $lecturer_image;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }
}
