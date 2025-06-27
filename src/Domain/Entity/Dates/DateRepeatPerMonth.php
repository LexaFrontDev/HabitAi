<?php

namespace App\Domain\Entity\Dates;


use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity, ORM\Table(name: "date_repeat_per_month")]
class DateRepeatPerMonth
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: "integer")]
    private ?int $id = null;



    #[ORM\Column(type: "integer")]
    private int $day;

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

    public function getDay(): int
    {
        return $this->day;
    }

    public function setDay(int $day): self
    {
        $this->day = $day;
        return $this;
    }
}