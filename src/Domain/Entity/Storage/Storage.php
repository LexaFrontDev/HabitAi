<?php

namespace App\Domain\Entity\Storage;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'storage')]
class Storage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(name: 'full_path', type: 'string', length: 255)]
    private string $fullPath;

    #[ORM\Column(type: 'string', length: 50)]
    private string $type;

    #[ORM\Column(name: 'file_type', type: 'string', length: 20)]
    private string $fileType;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\Column(type: 'boolean')]
    private bool $is_delete = false;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullPath(): string
    {
        return $this->fullPath;
    }

    public function setFullPath(string $fullPath): static
    {
        $this->fullPath = $fullPath;
        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;
        return $this;
    }


    public function getFileType(): int
    {
        return $this->fileType;
    }

    public function setFileType(int $fileType): static
    {
        $this->fileType = $fileType;
        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updated_at): self
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