<?php

namespace App\Aplication\Dto\FiltersWithCriterias\Habits;

use App\Aplication\CriteriaFilters\EntityFindById;
use App\Aplication\CriteriaFilters\EntityJoinCriteria;
use App\Aplication\CriteriaFilters\PaginationDto;

class FilterSelectHabitsStatisticAll
{
    public function __construct(
        public EntityFindById $user_id,
        public EntityJoinCriteria $join_habits,
        public PaginationDto $pagination,
    ) {
    }
}
