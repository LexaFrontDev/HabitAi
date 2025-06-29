<?php

namespace App\Domain\Entity\JunctionTabels\Habits;


use App\Domain\Repository\Dates\DataJunctionRepositoryInterface;
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
