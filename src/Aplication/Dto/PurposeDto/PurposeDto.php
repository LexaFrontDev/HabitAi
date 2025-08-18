<?php

namespace App\Aplication\Dto\PurposeDto;

class PurposeDto
{
    public function __construct(
        private int $habitsId,
        private string $type,
        private int $count,
        private bool $checkManually = false,
        private int $autoCount = 0,
        private bool $checkAuto = false,
        private bool $checkClose = false,
    ) {
    }

    public function getHabitsId(): int
    {
        return $this->habitsId;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getCount(): int
    {
        return $this->count;
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
