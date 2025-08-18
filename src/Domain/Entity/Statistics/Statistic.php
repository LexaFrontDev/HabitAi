<?php

namespace App\Domain\Entity\Statistics;

use App\Aplication\Dto\StatisticDto\StatisticYearStatisticDto;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'statistic')]
class Statistic
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private int $user_id;

    #[ORM\Column(length: 50, options: ['comment' => 'Тип статистики (habit, task, etc.)'])]
    private string $stat_type;

    #[ORM\Column(options: ['comment' => 'ID сущности, к которой относится статистика'])]
    private int $static_id;

    /** @var StatisticYearStatisticDto[] $year */
    #[ORM\Column(name: 'year', type: 'json')]
    private array $year = [];


    #[ORM\Column(name: 'created_at', type: 'datetime', nullable: true, options: ['comment' => 'Дата создания записи'])]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\Column(name: 'updated_at', type: 'datetime', nullable: true, options: ['comment' => 'Дата обновления записи'])]
    private ?\DateTimeInterface $updated_at = null;

    #[ORM\Column(name: 'is_delete', type: 'boolean', options: ['comment' => 'Флаг логического удаления'])]
    private bool $is_delete = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }

    /** @return StatisticYearStatisticDto[] */
    public function getYear(): array
    {
        return $this->year;
    }

    /**
     * @param StatisticYearStatisticDto[] $year
     */
    public function setYear(array $year): static
    {
        $this->year = $year;

        return $this;
    }

    public function getStaticId(): int
    {
        return $this->static_id;
    }

    public function setStaticId(int $static_id): void
    {
        $this->static_id = $static_id;
    }

    public function getStatType(): string
    {
        return $this->stat_type;
    }

    public function setStatType(string $stat_type): void
    {
        $this->stat_type = $stat_type;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): void
    {
        $this->updated_at = $updated_at;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeInterface $created_at): void
    {
        $this->created_at = $created_at;
    }
}
