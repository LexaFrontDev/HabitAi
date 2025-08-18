<?php

namespace App\Aplication\Dto\HabitsDto;

class RepSaveHabitsProgressDto
{
    public function __construct(
        public readonly ?int $id,
        public readonly ?int $count,
        public readonly ?int $habits_id,
        public readonly ?int $count_end,
        public readonly ?int $userId,
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCount(): ?int
    {
        return $this->count;
    }

    public function getHabitsId(): ?int
    {
        return $this->habits_id;
    }

    public function getCountEnd(): ?int
    {
        return $this->count_end;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }
}
