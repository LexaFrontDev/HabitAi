<?php

namespace App\Aplication\Dto\HabitsDto;

class SaveHabitsProgress
{
    public function __construct(
        public ?int $id,
        public ?int $habits_id,
        public ?int $count_end = null,
        public ?int $userId = null,
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
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
