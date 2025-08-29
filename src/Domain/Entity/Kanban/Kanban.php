<?php

namespace App\Domain\Entity\Kanban;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'kanban')]
class Kanban
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(name: 'list_id', type: 'integer', nullable: false)]
    private int $list_id;

    #[ORM\Column(name: 'user_id', type: 'integer', nullable: false)]
    private int $user_id;

    #[ORM\Column(name: 'kanban_name', type: 'string', length: 255, nullable: false)]
    private string $kanban_name;


    #[ORM\Column(name: 'created_at', type: 'datetime', nullable: true, options: ['comment' => 'Дата создания записи'])]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\Column(name: 'updated_at', type: 'datetime', nullable: true, options: ['comment' => 'Дата обновления записи'])]
    private ?\DateTimeInterface $updated_at = null;

    #[ORM\Column(name: 'is_delete', type: 'boolean', options: ['comment' => 'Флаг логического удаления'])]
    private bool $is_delete = false;

    // getters setters

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getKanbanName(): string
    {
        return $this->kanban_name;
    }

    public function getListId(): int
    {
        return $this->list_id;
    }

    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }

    public function setKanbanName(string $kanban_name): void
    {
        $this->kanban_name = $kanban_name;
    }

    public function setListId(int $list_id): void
    {
        $this->list_id = $list_id;
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
