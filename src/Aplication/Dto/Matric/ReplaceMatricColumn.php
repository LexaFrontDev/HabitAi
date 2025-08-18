<?php

namespace App\Aplication\Dto\Matric;

class ReplaceMatricColumn
{
    public function __construct(
        public readonly ?int $userId,
        public readonly ?int $taskId,
        public readonly ?int $columnNumber = null,
    ) {
    }
}
