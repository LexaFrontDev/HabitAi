<?php

namespace App\Aplication\Dto\PomodorDto;

class ResPomdorCountStatistic
{
    public function __construct(
        private ?string $periodLabel,
        private ?int $count,
        private ?string $target = 'Weak',
    ) {
    }

    public function getTarget(): ?string
    {
        return $this->target;
    }

    public function getPeriodLabel(): ?string
    {
        return $this->periodLabel;
    }

    public function getCount(): ?int
    {
        return $this->count;
    }
}
