<?php

namespace App\Domain\Entity\Dates;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity, ORM\Table(name: "date_weekly")]
class DateWeekly
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "integer")]
    private int $countDays;

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

    public function getCountDays(): int
    {
        return $this->countDays;
    }

    public function setCountDays(int $countDays): self
    {
        $this->countDays = $countDays;
        return $this;
    }
}