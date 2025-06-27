<?php

namespace App\Aplication\Dto\HabitsDtoUseCase;

class SaveHabitDto
{
    public function __construct(
        private string $titleHabit,
        private string $quote,
        private int $goalInDays,
        private int $beginDate,
        private string $notificationDate,
        private string $iconUrl,
        private int $userId,
    ) {}

    public function getTitleHabit(): string
    {
        return $this->titleHabit;
    }

    public function getQuote(): string
    {
        return $this->quote;
    }

    public function getGoalInDays(): int
    {
        return $this->goalInDays;
    }

    public function getBeginDate(): int
    {
        return $this->beginDate;
    }

    public function getNotificationDate(): string
    {
        return $this->notificationDate;
    }

    public function getIconUrl(): string
    {
        return $this->iconUrl;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }
}
