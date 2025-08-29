<?php

namespace App\Domain\Entity\Kanban;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'kanban_labels')]
class KanbanLabels
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(name: 'kanban_id', type: 'integer', nullable: false)]
    private int $kanban_id;

    #[ORM\Column(name: 'user_id', type: 'integer', nullable: false)]
    private int $user_id;


    #[ORM\Column(name: 'kanban_label', type: 'string', length: 255, nullable: false)]
    private string $kanban_label;


    #[ORM\Column(name: 'place', type: 'integer', nullable: false)]
    private int $place;

    #[ORM\Column(name: 'created_at', type: 'datetime', nullable: true, options: ['comment' => 'Дата создания записи'])]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\Column(name: 'updated_at', type: 'datetime', nullable: true, options: ['comment' => 'Дата обновления записи'])]
    private ?\DateTimeInterface $updated_at = null;

    #[ORM\Column(name: 'is_delete', type: 'boolean', options: ['comment' => 'Флаг логического удаления'])]
    private bool $is_delete = false;

    // getter setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }

    public function getKanbanId(): int
    {
        return $this->kanban_id;
    }

    public function setKanbanId(int $kanban_id): void
    {
        $this->kanban_id = $kanban_id;
    }

    public function getKanbanLabel(): string
    {
        return $this->kanban_label;
    }

    public function setKanbanLabel(string $kanban_label): void
    {
        $this->kanban_label = $kanban_label;
    }

    public function getPlace(): int
    {
        return $this->place;
    }

    public function setPlace(int $place): void
    {
        $this->place = $place;
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
