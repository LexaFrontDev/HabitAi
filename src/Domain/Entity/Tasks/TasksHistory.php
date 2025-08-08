<?php

namespace App\Domain\Entity\Tasks;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "tasks_history")]
class TasksHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer", options: ['comment' => 'ID записи истории задачи'])]
    private ?int $id = null;

    #[ORM\Column(name: "user_id", type: "integer", options: ['comment' => 'ID пользователя'])]
    private ?int $user_id;

    #[ORM\Column(name: "tasks_id", type: "integer", options: ['comment' => 'ID задачи'])]
    private ?int $tasks_id = null;

    #[ORM\Column(name: "time_close", type: "datetime", nullable: true, options: ['comment' => 'Дата и время закрытия задачи'])]
    private ?DateTimeInterface $time_close = null;

    #[ORM\Column(name: "time_close_month", type: "string", nullable: true, options: ['comment' => 'Месяц закрытия задачи (например, 2025-08)'])]
    private ?string $time_close_month = null;

    #[ORM\Column(name: 'created_at', type: 'datetime', nullable: true, options: ['comment' => 'Дата создания записи'])]
    private ?DateTimeInterface $created_at = null;

    #[ORM\Column(name: 'updated_at', type: 'datetime', nullable: true, options: ['comment' => 'Дата обновления записи'])]
    private ?DateTimeInterface $updated_at = null;

    #[ORM\Column(name: 'is_delete', type: 'boolean', options: ['comment' => 'Флаг логического удаления'])]
    private bool $is_delete = false;




    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * @return int|null
     */
    public function getTasksId(): ?int
    {
        return $this->tasks_id;
    }


    /**
     * @param int|null $tasks_id
     */
    public function setTasksId(?int $tasks_id): void
    {
        $this->tasks_id = $tasks_id;
    }


    /**
     * @return int|null
     */
    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    /**
     * @param int|null $user_id
     */
    public function setUserId(?int $user_id): void
    {
        $this->user_id = $user_id;
    }

    public function removeTask(Task $task): void
    {
        $this->tasks->removeElement($task);
    }

    public function setTimeClose(?DateTimeInterface $time_close): void
    {
        $this->time_close = $time_close;
    }

    public function getTimeClose(): ?DateTimeInterface
    {
        return $this->time_close;
    }

    public function setTimeCloseMonth(?string $time_close_month): void
    {
        $this->time_close_month = $time_close_month;
    }

    public function getTimeCloseMonth(): ?string
    {
        return $this->time_close_month;
    }

    public function setCreatedAt(?DateTimeInterface $created_at): void
    {
        $this->created_at = $created_at;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->created_at;
    }

    public function setUpdatedAt(?DateTimeInterface $updated_at): void
    {
        $this->updated_at = $updated_at;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setIsDelete(bool $is_delete): void
    {
        $this->is_delete = $is_delete;
    }

    public function isDelete(): bool
    {
        return $this->is_delete;
    }
}
