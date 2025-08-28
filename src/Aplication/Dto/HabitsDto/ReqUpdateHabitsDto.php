<?php

namespace App\Aplication\Dto\HabitsDto;

class ReqUpdateHabitsDto
{
    /**
     * @param array<int, mixed> $date
     */
    public function __construct(
        public readonly int $habitId,
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
}
