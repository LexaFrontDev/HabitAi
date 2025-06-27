<?php

namespace App\Aplication\Dto\PomodorDto;

class ResPomodorDto
{

    public function __construct(
        private int $time_label,
        private int $timeFocus,
        private int $timeStart,
        private int $timeEnd,
    ){}


}