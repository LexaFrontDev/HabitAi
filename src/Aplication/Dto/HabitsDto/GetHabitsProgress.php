<?php

namespace App\Aplication\Dto\HabitsDto;

class GetHabitsProgress
{
    public function __construct(
        public readonly int $id,
        public readonly int $habitId,
        public readonly int $count,
        public readonly int $countEnd,
        public readonly bool $isDone,
        public readonly \DateTimeInterface $recordedAt,
    ) {
    }
}
