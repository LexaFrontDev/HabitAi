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
}
