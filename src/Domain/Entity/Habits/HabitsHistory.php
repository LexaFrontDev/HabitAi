<?php

namespace App\Domain\Entity\Habits;

use App\Domain\Repository\Habits\HabitsHistoryRepositoryInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ORM\Index(columns: ['user_id'], name: 'idx_user')]
#[ORM\Index(columns: ['recorded_at'], name: 'idx_recorded_at')]
#[ORM\Index(columns: ['user_id', 'recorded_at'], name: 'idx_user_date')]
class HabitsHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $count = null;

    #[ORM\Column]
    private ?int $habits_id = null;

    #[ORM\Column]
    private ?int $count_end = null;


    #[ORM\Column]
    private ?bool $isDone = false;

    #[ORM\Column(name: 'user_id')]
    private ?int $userId = null;

    #[ORM\Column(name: 'recorded_at', type: 'datetime')]
    private ?\DateTimeInterface $recordedAt = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $created_at;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\Column]
    private bool $is_deleted = false;

    public function __construct()
    {
        $this->created_at = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updated_at = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCount(): ?int
    {
        return $this->count;
    }

    public function setCount(int $count): static
    {
        $this->count = $count;

        return $this;
    }

    public function getHabitsId(): ?int
    {
        return $this->habits_id;
    }

    public function setHabitsId(int $habits_id): static
    {
        $this->habits_id = $habits_id;

        return $this;
    }

    public function getCountEnd(): ?int
    {
        return $this->count_end;
    }

    public function setCountEnd(int $count_end): static
    {
        $this->count_end = $count_end;

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function is_deleted(): bool
    {
        return $this->is_deleted;
    }

    public function setis_deleted(bool $is_deleted): static
    {
        $this->is_deleted = $is_deleted;

        return $this;
    }


    /**
     * @param bool|null $isDone
     */
    public function setIsDone(?bool $isDone): void
    {
        $this->isDone = $isDone;
    }

    /**
     * @return bool|null
     */
    public function getIsDone(): ?bool
    {
        return $this->isDone;
    }

    /**
     * @return int|null
     */
    public function getUserId(): ?int
    {
        return $this->userId;
    }

    /**
     * @param int|null $userId
     */
    public function setUserId(?int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getRecordedAt(): ?\DateTimeInterface
    {
        return $this->recordedAt;
    }

    /**
     * @param \DateTimeInterface|null $recordedAt
     */
    public function setRecordedAt(?\DateTimeInterface $recordedAt): void
    {
        $this->recordedAt = $recordedAt;
    }





}
