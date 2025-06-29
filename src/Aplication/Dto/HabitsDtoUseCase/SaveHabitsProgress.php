<?php

namespace App\Aplication\Dto\HabitsDtoUseCase;

class SaveHabitsProgress
{

    public function __construct(
        public ?int $id = null,
        public ?int $habits_id,
        public ?int $count_end = null,
        public ?int $userId = null,
    ){}


    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * @return int|null
     */
    public function getCount(): ?int
    {
        return $this->count;
    }

    public function getHabitsId(): ?int
    {
        return $this->habits_id;
    }

    /**
     * @return int|null
     */
    public function getCountEnd(): ?int
    {
        return $this->count_end;
    }

    /**
     * @return bool|null
     */
    public function getIsDone(): ?bool
    {
        return $this->isDone;
    }


    /**
     * @return int|null
     */
    public function getUserId(): ?int
    {
        return $this->userId;
    }
}