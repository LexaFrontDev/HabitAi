<?php

namespace App\Aplication\CriteriaFilters;

final class OrderByDto
{
    public function __construct(
        public string $field,
        public string $direction = OrderDirection::DESC,
    ) {
    }
}
