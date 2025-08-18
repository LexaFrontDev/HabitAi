<?php

namespace App\Domain\Entity\Pomdoro;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'pomodoro_history')]
class PomodorHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private int $user_id;

    #[ORM\Column]
    private int $time_focus;


    #[ORM\Column(length: 120)]
    private string $title = 'Фокус';

    #[ORM\Column(length: 10)]
    private string $period_label;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $focus_start;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $focus_end;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $create_date;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTime $update_date = null;

    #[ORM\Column]
    private int $is_delete = 0;

    public function getId(): int
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

    public function getTimeFocus(): int
    {
        return $this->time_focus;
    }

    public function setTimeFocus(int $time_focus): static
    {
        $this->time_focus = $time_focus;

        return $this;
    }

    public function getPeriodLabel(): string
    {
        return $this->period_label;
    }

    public function setPeriodLabel(string $period_label): static
    {
        $this->period_label = $period_label;

        return $this;
    }

    public function getFocusStart(): \DateTime
    {
        return $this->focus_start;
    }

    public function setFocusStart(\DateTime $focus_start): static
    {
        $this->focus_start = $focus_start;

        return $this;
    }

    public function getFocusEnd(): \DateTime
    {
        return $this->focus_end;
    }

    public function setFocusEnd(\DateTime $focus_end): static
    {
        $this->focus_end = $focus_end;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getCreateDate(): \DateTime
    {
        return $this->create_date;
    }

    public function setCreateDate(\DateTime $create_date): static
    {
        $this->create_date = $create_date;

        return $this;
    }

    public function getUpdateDate(): ?\DateTime
    {
        return $this->update_date;
    }

    public function setUpdateDate(?\DateTime $update_date): static
    {
        $this->update_date = $update_date;

        return $this;
    }

    public function isDelete(): int
    {
        return $this->is_delete;
    }

    public function setis_delete(int $is_delete): static
    {
        $this->is_delete = $is_delete;

        return $this;
    }
}
