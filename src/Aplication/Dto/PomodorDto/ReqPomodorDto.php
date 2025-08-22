<?php

namespace App\Aplication\Dto\PomodorDto;

class ReqPomodorDto
{
    public function __construct(
        public readonly string $title,
        public readonly int $timeFocus,
        public readonly int $timeStart,
        public readonly int $timeEnd,
        public readonly int $created_date,
    ) {
    }

    public function getTimeFocus(): int
    {
        return $this->timeFocus;
    }

    public function getTimeStart(): int
    {
        return $this->timeStart;
    }

    public function getTimeEnd(): int
    {
        return $this->timeEnd;
    }

    public function getCreatedDate(): int
    {
        return $this->created_date;
    }
}
