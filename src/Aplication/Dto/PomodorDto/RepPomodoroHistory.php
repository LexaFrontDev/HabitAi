<?php

namespace App\Aplication\Dto\PomodorDto;

class RepPomodoroHistory
{
    public function __construct(
        private ?string $periodLabel,
        private ?string $startTime,
        private ?string $endTime,
        private ?string $title = null,
    ){}

    /**
     * @return string|null
     */
    public function getEndTime(): ?string
    {
        return $this->endTime;
    }

    public function getPeriodLabel(): ?string
    {
        return $this->periodLabel;
    }

    public function getStartTime(): ?string
    {
        return $this->startTime;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }


}