<?php

namespace App\Entity\DTO;

use App\Entity\Code;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;

class CodeDTO
{
    private ?int $id = null;

    private ?string $hash = null;

    private ?string $url = null;

    private ?ParticipantDTO $used_by = null;

    private ?int $lecture_id = null;

    private ?\DateTimeImmutable $created_at = null;

    private ?\DateTimeInterface $updated_at = null;

    public function __construct( ?Code $code = null )
    {
        if( isset( $code ) )
        {
            $this->setId( $code->getId() );
            $this->setHash( $code->getHash() );
            $this->setUrl( $code->getUrl() );
            $this->setUsedBy( $code->getUsedBy() );
            $this->setLecture( $code->getLecture()->getId() );
            $this->setCreatedAt( $code->getCreatedAt() );
            $this->setUpdatedAt( $code->getUpdatedAt() );
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

    public function getUsedBy(): ?ParticipantDTO
    {
        return $this->used_by;
    }

    public function setUsedBy($used_by): self
    {
        if( is_int( $used_by ) )
        {
            $this->used_by = new ParticipantDTO();
            $this->used_by->setId( $used_by );
        }
        elseif( is_a( $used_by, 'ParticipantDTO' ) )
        {
            $this->used_by = $used_by;
        }
        elseif( is_a( $used_by, 'Participant' ) )
        {
            $this->used_by = new ParticipantDTO( $used_by );
        }
        
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
