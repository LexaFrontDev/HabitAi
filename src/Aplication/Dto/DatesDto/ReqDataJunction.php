<?php

namespace App\Aplication\Dto\DatesDto;

class ReqDataJunction
{
    public function __construct(
        private int $habitsId,
        private int $dataId,
        private string $dataType,
    ) {
    }

    public function getHabitsId(): int
    {
        return $this->habitsId;
    }

    public function getDataId(): int
    {
        return $this->dataId;
    }

    public function getDataType(): string
    {
        return $this->dataType;
    }
}
