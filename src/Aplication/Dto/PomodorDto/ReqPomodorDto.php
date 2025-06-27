<?php

namespace App\Aplication\Dto\PomodorDto;

class ReqPomodorDto
{

    public function __construct(
        private int $userId,
        private int $timeFocus,
        private int $timeStart,
        private int $timeEnd,
        private int $created_date
    ){}


    public function getUserId(): int
    {
        return $this->userId;
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