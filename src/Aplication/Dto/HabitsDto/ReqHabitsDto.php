<?php

namespace App\Aplication\Dto\HabitsDto;

class ReqHabitsDto
{
    /**
     * @param array<int, mixed> $date
     */
    public function __construct(
        public readonly string $title,
        public readonly string $quote,
        public readonly string $goalInDays,
        public readonly string $datesType,
        public readonly array $date,
        public readonly int $beginDate,
        public readonly string $notificationDate,
        public readonly string $purposeType,
        public readonly int $purposeCount,
        public readonly bool $checkManually = false,
        public readonly int $autoCount = 0,
        public readonly bool $checkAuto = false,
        public readonly bool $checkClose = false,
    ) {
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getQuote(): string
    {
        return $this->quote;
    }

    public function getDatesType(): string
    {
        return $this->datesType;
    }

    /**
     * @return array<int, mixed>
     */
    public function getDate(): array
    {
        return $this->date;
    }

    public function getBeginDate(): int
    {
        return $this->beginDate;
    }

    public function getNotificationDate(): string
    {
        return $this->notificationDate;
    }

    public function getGoalInDays(): string
    {
        return $this->goalInDays;
    }

    public function getPurposeType(): string
    {
        return $this->purposeType;
    }

    public function getPurposeCount(): int
    {
        return $this->purposeCount;
    }

    public function isCheckManually(): bool
    {
        return $this->checkManually;
    }

    public function isCheckAuto(): bool
    {
        return $this->checkAuto;
    }

    public function isCheckClose(): bool
    {
        return $this->checkClose;
    }

    public function getautoCount(): int
    {
        return $this->autoCount;
    }
}
