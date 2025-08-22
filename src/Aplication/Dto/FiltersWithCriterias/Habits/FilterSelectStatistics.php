<?php

namespace App\Aplication\Dto\FiltersWithCriterias\Habits;

use App\Aplication\CriteriaFilters\EntityFindById;
use App\Aplication\CriteriaFilters\EntityFindByString;
use App\Aplication\CriteriaFilters\PaginationDto;

class FilterSelectStatistics
{
    public function __construct(
        public EntityFindById $user_id,
        public EntityFindByString $year,
        public EntityFindByString $stat_type,
        public PaginationDto $pagination,
    ) {
    }


}
