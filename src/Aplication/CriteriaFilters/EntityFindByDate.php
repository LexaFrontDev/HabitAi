<?php

namespace App\Aplication\CriteriaFilters;

class EntityFindByDate
{
    public function __construct(
        public string $YmdDate = '',
        public string $YmdTime = '',
        public string $YmdTimeStamp = '',
    ) {
    }
}
