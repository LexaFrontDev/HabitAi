<?php

namespace App\Domain\Entity\JunctionTabels\Habits;


use App\Domain\Repository\DatesRepository\DataJunctionRepositoryInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DataJunctionRepositoryInterface::class)]
class HabitsDataJuntion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $habitsId = null;

    #[ORM\Column]
    private ?int $data_id = null;

    #[ORM\Column(length: 255)]
    private ?string $data_type = 'daily';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHabitsId(): ?int
    {
        return $this->habitsId;
    }

    public function setHabitsId(int $habitsId): static
    {
        $this->habitsId = $habitsId;

        return $this;
    }

    public function getDataId(): ?int
    {
        return $this->data_id;
    }

    public function setDataId(int $data_id): static
    {
        $this->data_id = $data_id;

        return $this;
    }

    public function getDataType(): ?string
    {
        return $this->data_type;
    }

    public function setDataType(string $data_type): static
    {
        $this->data_type = $data_type;

        return $this;
    }
}
