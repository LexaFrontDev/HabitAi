<?php

namespace App\Domain\Entity\Dates;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity, ORM\Table(name: 'date_daily')]
class DateDaily
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'boolean')]
    private bool $mon;

    #[ORM\Column(type: 'boolean')]
    private bool $tue;

    #[ORM\Column(type: 'boolean')]
    private bool $wed;

    #[ORM\Column(type: 'boolean')]
    private bool $thu;

    #[ORM\Column(type: 'boolean')]
    private bool $fri;

    #[ORM\Column(type: 'boolean')]
    private bool $sat;

    #[ORM\Column(type: 'boolean')]
    private bool $sun;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $updated_at = null;

    #[ORM\Column(type: 'boolean')]
    private bool $is_delete = false;

    // ======= Getters & Setters =======

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function isMon(): bool
    {
        return $this->mon;
    }

    public function setMon(bool $mon): self
    {
        $this->mon = $mon;

        return $this;
    }

    public function isTue(): bool
    {
        return $this->tue;
    }

    public function setTue(bool $tue): self
    {
        $this->tue = $tue;

        return $this;
    }

    public function isWed(): bool
    {
        return $this->wed;
    }

    public function setWed(bool $wed): self
    {
        $this->wed = $wed;

        return $this;
    }

    public function isThu(): bool
    {
        return $this->thu;
    }

    public function setThu(bool $thu): self
    {
        $this->thu = $thu;

        return $this;
    }

    public function isFri(): bool
    {
        return $this->fri;
    }

    public function setFri(bool $fri): self
    {
        $this->fri = $fri;

        return $this;
    }

    public function isSat(): bool
    {
        return $this->sat;
    }

    public function setSat(bool $sat): self
    {
        $this->sat = $sat;

        return $this;
    }

    public function isSun(): bool
    {
        return $this->sun;
    }

    public function setSun(bool $sun): self
    {
        $this->sun = $sun;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getis_delete(): bool
    {
        return $this->is_delete;
    }

    public function setis_delete(bool $is_delete): self
    {
        $this->is_delete = $is_delete;

        return $this;
    }
}
