<?php

namespace App\Entity\DTO;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;

class ParticipantDTO
{
    private ?int $id = null;

    private ?string $kind = null;

    private ?string $ra = null;

    private ?string $name = null;

    private ?string $email = null;

    private ?string $reason = null;

    private ?string $accepted_terms = null;

    private ?string $user_agent = null;

    private ?string $ip = null;

    private ?\DateTimeImmutable $created_at = null;

    private ?\DateTimeInterface $updated_at = null;

    private ?int $course_id;

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

    public function getKind(): ?string
    {
        return $this->kind;
    }

    public function setKind(string $kind): self
    {
        $this->kind = $kind;

        return $this;
    }

    public function getRa(): ?string
    {
        return $this->ra;
    }

    public function setRa(string $ra): self
    {
        $this->ra = $ra;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(?string $reason): self
    {
        $this->reason = $reason;

        return $this;
    }

    public function getAcceptedTerms(): ?string
    {
        return $this->accepted_terms;
    }

    public function setAcceptedTerms(string $accepted_terms): self
    {
        $this->accepted_terms = $accepted_terms;

        return $this;
    }

    public function getUserAgent(): ?string
    {
        return $this->user_agent;
    }

    public function setUserAgent(string $user_agent): self
    {
        $this->user_agent = $user_agent;

        return $this;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(string $ip): self
    {
        $this->ip = $ip;

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

    public function setCourseId(int $courseId): self
    {
        $this->setCourseId( $courseId );
        return $this;
    }

    public function getCourseId(): int
    {
        return $this->course_id;
    }


}
