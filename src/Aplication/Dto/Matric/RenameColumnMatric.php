<?php

namespace App\Aplication\Dto\Matric;

class RenameColumnMatric
{
    public function __construct(
        public readonly ?int $userId,
        public readonly ?string $firstColumnName,
        public readonly ?string $secondColumnName,
        public readonly ?string $thirdColumnName,
        public readonly ?string $fourthColumnName,
    ) {
    }



}
