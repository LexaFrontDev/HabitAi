<?php

namespace App\Aplication\Dto\ConditionsDto;

use App\Domain\Exception\Message\MessageException;

class OnConditionDto
{
    public function __construct(
        public readonly ?string $expr = null,
        public readonly ?string $left = null,
        public readonly ?string $operator = null,
        public readonly ?string $right = null,
        public readonly ?string $rightParam = null,
        public readonly string $type = 'AND',
    ) {
        if (null === $expr && null === $left) {
            throw new MessageException('OnConditionDto должен содержать либо expr, либо left/operator/right');
        }
    }

    public function isRawExpr(): bool
    {
        return null !== $this->expr;
    }
}
