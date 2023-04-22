<?php

namespace App\Entity\DTO;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;

class EventDTO
{
    #[OA\Property(type: 'integer', description: 'The unique identifier of the event.')]
    private ?int $id = null;

    #[OA\Property(type: 'string', description: 'The event name.')]
    private ?string $name = null;

    #[OA\Property(type: 'string', description: 'The event description.')]
    private ?string $description = null;

    #[OA\Property(type: 'DateTimeInterface', description: 'The event start date.')]
    private ?\DateTimeInterface $start_date = null;

    #[OA\Property(type: 'DateTimeInterface', description: 'The event end date.')]
    private ?\DateTimeInterface $end_date = null;

    #[OA\Property(type: 'string', description: 'The event banner URL.')]
    private ?string $banner_url = null;

    #[OA\Property(type: 'DateTimeImmutable', description: 'Record creation date.')]
    private ?\DateTimeImmutable $created_at = null;

    #[OA\Property(type: 'DateTimeInterface', description: 'Date of last record update.')]
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getBannerUrl(): ?string
    {
        return $this->banner_url;
    }

    public function setBannerUrl(?string $banner_url): self
    {
        $this->banner_url = $banner_url;

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
