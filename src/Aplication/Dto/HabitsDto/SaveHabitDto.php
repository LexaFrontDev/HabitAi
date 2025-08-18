<?php

namespace App\Aplication\Dto\HabitsDto;

class SaveHabitDto
{
    public function __construct(
        public readonly string $titleHabit,
        public readonly string $quote,
        public readonly string $goalInDays,
        public readonly int $beginDate,
        public readonly string $notificationDate,
        public readonly string $iconUrl,
        public readonly int $userId,
    ) {
    }

    public function getTitleHabit(): string
    {
        return $this->titleHabit;
    }

    public function getQuote(): string
    {
        return $this->quote;
    }

    public function getGoalInDays(): string
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
