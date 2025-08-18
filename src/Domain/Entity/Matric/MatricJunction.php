<?php

namespace App\Domain\Entity\Matric;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'matric_junction')]
class MatricJunction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private int $columnNumber;

    #[ORM\Column]
    private int $taskNumber;

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

    public function getColumnNumber(): int
    {
        return $this->columnNumber;
    }

    public function setColumnNumber(int $number): static
    {
        $this->columnNumber = $number;

        return $this;
    }

    public function getTaskNumber(): int
    {
        return $this->taskNumber;
    }

    public function setTaskNumber(int $number): static
    {
        $this->taskNumber = $number;

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
