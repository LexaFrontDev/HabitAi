<?php

namespace App\Aplication\CriteriaFilters;

final class EntityFindByLike
{
    public function __construct(
        public string $like = '',
        public string $notLike = '',
    ) {
    }
}
