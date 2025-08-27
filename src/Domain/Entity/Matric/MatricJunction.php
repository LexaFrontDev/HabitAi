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
    private int $user_id;


    #[ORM\Column]
    private int $column_number;

    #[ORM\Column]
    private int $task_id;

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
        return $this->column_number;
    }

    public function setColumnNumber(int $number): static
    {
        $this->column_number = $number;

        return $this;
    }

    public function getTaskNumber(): int
    {
        return $this->task_id;
    }

    public function setTaskNumber(int $number): static
    {
        $this->task_id = $number;

        return $this;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
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
