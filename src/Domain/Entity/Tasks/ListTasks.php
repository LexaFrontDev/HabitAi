<?php

namespace App\Domain\Entity\Tasks;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'list_tasks')]
#[ORM\HasLifecycleCallbacks]
class ListTasks
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(name: 'user_id', type: 'integer', nullable: false)]
    private int $user_id;

    #[ORM\Column(name: 'label', type: 'string', length: 255, nullable: false)]
    private string $label;

    #[ORM\Column(name: 'priority', type: 'integer', nullable: false)]
    private int $priority;

    #[ORM\Column(name: 'list_type', type: 'string', length: 255, nullable: false)]
    private string $list_type;

    #[ORM\Column(name: 'created_at', type: 'datetime', nullable: false, options: ['comment' => 'Дата создания записи'])]
    private \DateTimeInterface $created_at;

    #[ORM\Column(name: 'updated_at', type: 'datetime', nullable: true, options: ['comment' => 'Дата обновления записи'])]
    private ?\DateTimeInterface $updated_at = null;

    #[ORM\Column(name: 'is_delete', type: 'boolean', options: ['comment' => 'Флаг логического удаления'])]
    private bool $is_delete = false;

    public function __construct(int $user_id, string $label, int $priority, string $list_type)
    {
        $this->user_id = $user_id;
        $this->label = $label;
        $this->priority = $priority;
        $this->list_type = $list_type;
        $this->created_at = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function setUpdatedAt(): void
    {
        $this->updated_at = new \DateTimeImmutable();
    }

    public function rename(string $label): void
    {
        $this->label = $label;
        $this->touch();
    }

    public function changePriority(int $priority): void
    {
        if ($priority < 1 || $priority > 5) {
            throw new \InvalidArgumentException('Priority must be between 1 and 5');
        }
        $this->priority = $priority;
        $this->touch();
    }

    public function changeType(string $list_type): void
    {
        $this->list_type = $list_type;
        $this->touch();
    }

    public function markAsDeleted(): void
    {
        $this->is_delete = true;
        $this->touch();
    }

    private function touch(): void
    {
        $this->updated_at = new \DateTimeImmutable();
    }
}
