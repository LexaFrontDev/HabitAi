<?php

namespace App\Domain\Entity\Dates;


use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity, ORM\Table(name: "date_daily")]
class DateDaily
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "boolean")]
    private bool $mon;

    #[ORM\Column(type: "boolean")]
    private bool $tue;

    #[ORM\Column(type: "boolean")]
    private bool $wed;

    #[ORM\Column(type: "boolean")]
    private bool $thu;

    #[ORM\Column(type: "boolean")]
    private bool $fri;

    #[ORM\Column(type: "boolean")]
    private bool $sat;

    #[ORM\Column(type: "boolean")]
    private bool $sun;

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

    public function getHabitId(): int
    {
        return $this->habitId;
    }

    public function setHabitId(int $habitId): self
    {
        $this->habitId = $habitId;
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
}