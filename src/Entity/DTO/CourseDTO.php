<?php

namespace App\Entity\DTO;

use App\Entity\Course;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;

class CourseDTO
{
    private ?int $id = null;

    private ?string $class = null;

    private ?string $period = null;

    private ?\DateTimeImmutable $created_at = null;

    private ?\DateTimeInterface $updated_at = null;

    public function __construct( ?Course $course = null )
    {
        if( isset( $course ) )
        {
            $this->setId( $course->getId() );
            $this->setClass( $course->getClass() );
            $this->setPeriod( $course->getPeriod() );
            $this->setCreatedAt( $course->getCreatedAt() );
            $this->setUpdatedAt( $course->getUpdatedAt() );
        }

        return $this;
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
