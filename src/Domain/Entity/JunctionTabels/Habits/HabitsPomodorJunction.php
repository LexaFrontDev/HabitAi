<?php

namespace App\Domain\Entity\JunctionTabels\Habits;

use App\Repository\HabitsPomodorJunctionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HabitsPomodorJunctionRepository::class)]
class HabitsPomodorJunction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private  ?int $pomodorId;

    #[ORM\Column]
    private  ?int $habitsId;

#[ORM\Column(type: 'datetime', nullable: true)]
private ?\DateTimeInterface $created_at = null;

   #[ORM\Column(type: 'datetime', nullable: true)]
private ?\DateTimeInterface $updated_at = null;

    #[ORM\Column(type: 'boolean')]
    private bool $is_delete = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPomodorId(): ?int
    {
        return $this->pomodorId;
    }

    public function setPomodorId(?int $pomodorId): self
    {
        $this->pomodorId = $pomodorId;
        return $this;
    }


    public function getHabitsId(): ?int
    {
        return $this->habitsId;
    }

    public function setHabitsId(?int $habitsId): self
    {
        $this->habitsId = $habitsId;
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
