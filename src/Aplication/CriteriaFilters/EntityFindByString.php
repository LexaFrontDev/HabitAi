<?php

namespace App\Aplication\CriteriaFilters;

class EntityFindByString
{
    /**
     * @param string[] $anyOf
     * @param string[] $in
     */
    public function __construct(
        public string $equal = '',
        public array $anyOf = [],
        public array $in = [],
    ) {
    }
}
