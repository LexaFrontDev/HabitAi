<?php

namespace App\Aplication\CriteriaFilters;

class PaginationDto
{
    public function __construct(
        public ?int $limit = null,
        public ?int $offset = null,
        public ?int $isDelete = 1,
        public bool $paginationEnabled = false,
    ) {
    }
}
