<?php

namespace App\Entity\DTO;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;

class CourseDTO
{
    #[OA\Property(type: 'integer', description: 'The unique identifier of the course.')]
    private ?int $id = null;

    #[OA\Property(type: 'string', description: 'The class name.')]
    private ?string $class = null;

    #[OA\Property(type: 'string', description: 'The period name.')]
    private ?string $period = null;

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

    public function getClass(): ?string
    {
        return $this->class;
    }

    public function setClass(string $class): self
    {
        $this->class = $class;

        return $this;
    }

    public function getPeriod(): ?string
    {
        return $this->period;
    }

    public function setPeriod(string $period): self
    {
        $this->period = $period;

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
