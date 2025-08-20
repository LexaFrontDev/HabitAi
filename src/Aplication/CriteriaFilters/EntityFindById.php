<?php

namespace App\Aplication\CriteriaFilters;

final class EntityFindById
{
    /**
     * @param int[] $in
     * @param int[] $notIn
     */
    public function __construct(
        public ?int $id = null,
        public array $in = [],
        public array $notIn = [],
    ) {
    }
}
