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