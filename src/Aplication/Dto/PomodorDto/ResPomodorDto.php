<?php

namespace App\Aplication\Dto\PomodorDto;

class ResPomodorDto
{
    public function __construct(
        public readonly int $time_label,
        public readonly int $timeFocus,
        public readonly int $timeStart,
        public readonly int $timeEnd,
    ) {
    }


}
