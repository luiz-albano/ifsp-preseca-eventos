<?php

namespace App\Entity\DTO;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;

class CodeDTO
{
    private ?int $id = null;

    private ?string $hash = null;

    private ?string $url = null;

    private ?int $used_by = null;

    private ?int $lecture_id = null;

    private ?\DateTimeImmutable $created_at = null;

    private ?\DateTimeInterface $updated_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;
        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;
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

    public function getUsedBy(): ?int
    {
        return $this->used_by;
    }

    public function setUsedBy(?int $used_by): self
    {
        $this->used_by = $used_by;
        return $this;
    }

    public function getLectureId(): ?int
    {
        return $this->lecture_id;
    }

    public function setLecture(?int $lecture): self
    {
        $this->lecture_id = $lecture;
        return $this;
    }

}
