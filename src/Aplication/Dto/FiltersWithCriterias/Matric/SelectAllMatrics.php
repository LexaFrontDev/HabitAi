<?php

namespace App\Aplication\Dto\FiltersWithCriterias\Matric;

use App\Aplication\CriteriaFilters\EntityFindById;
use App\Aplication\CriteriaFilters\EntityJoinCriteria;
use App\Aplication\CriteriaFilters\PaginationDto;

class SelectAllMatrics
{
    public function __construct(
        public readonly EntityFindById $user_id,
        public readonly EntityJoinCriteria $Tasks,
        public readonly PaginationDto $paginationDto,
    ) {
    }

}
