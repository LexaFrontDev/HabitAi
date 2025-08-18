<?php

namespace App\Domain\Entity\Matric;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'matric_column')]
class MatricColumn
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private int $userId;

    #[ORM\Column(length: 255)]
    private string $firstColumnName;

    #[ORM\Column(length: 255)]
    private string $secondColumnName;

    #[ORM\Column(length: 255)]
    private string $thirdColumnName;

    #[ORM\Column(length: 255)]
    private string $fourthColumnName;

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

    public function getFirstColumnName(): string
    {
        return $this->firstColumnName;
    }

    public function setFirstColumnName(string $name): static
    {
        $this->firstColumnName = $name;

        return $this;
    }

    public function getSecondColumnName(): string
    {
        return $this->secondColumnName;
    }

    public function setSecondColumnName(string $name): static
    {
        $this->secondColumnName = $name;

        return $this;
    }

    public function getThirdColumnName(): string
    {
        return $this->thirdColumnName;
    }

    public function setThirdColumnName(string $name): static
    {
        $this->thirdColumnName = $name;

        return $this;
    }

    public function getFourthColumnName(): string
    {
        return $this->fourthColumnName;
    }

    public function setFourthColumnName(string $name): static
    {
        $this->fourthColumnName = $name;

        return $this;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function getUserId(): int
    {
        return $this->userId;
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
