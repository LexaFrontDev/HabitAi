<?php

namespace App\Aplication\Dto\HabitsDtoUseCase;

class  ReqHabitsDto
{

    public function __construct(
        private string $titleHabit,
        private string $quote,
        private string $goalInDays,
        private string $datesType,
        private array $date,
        private int $beginDate,
        private string $notificationDate,
        private string $purposeType,
        private int $purposeCount,
        private bool $checkManually = false,
        private int $autoCount = 0,
        private bool $checkAuto = false,
        private bool $checkClose = false,
        private string $iconBase64 = '',
    ){}


    /**
     * @return string
     */
    public function getTitleHabit(): string
    {
        return $this->titleHabit;
    }

    public function getIconBase64(): string
    {
        return $this->iconBase64;
    }

    public function getQuote(): string
    {
        return $this->quote;
    }

    public function getDatesType(): string
    {
        return $this->datesType;
    }

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


    /**
     * @return string
     */
    public function getGoalInDays(): string
    {
        return $this->goalInDays;
    }


    /**
     * @return string
     */
    public function getPurposeType(): string
    {
        return $this->purposeType;
    }


    /**
     * @return int
     */
    public function getPurposeCount(): int
    {
        return $this->purposeCount;
    }


    /**
     * @return bool
     */
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