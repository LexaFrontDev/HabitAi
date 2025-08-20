<?php

namespace App\Aplication\Dto\FiltersWithCriterias;

use App\Aplication\CriteriaFilters\EntityFindById;
use App\Aplication\CriteriaFilters\EntityFindByString;
use App\Aplication\CriteriaFilters\EntityJoinCriteria;
use App\Aplication\CriteriaFilters\PaginationDto;

class HabitsSelectCriteria
{
    public function __construct(
        public EntityFindById $habitsId,
        public EntityFindById $userId,
        public EntityFindByString $date,
        public EntityJoinCriteria $purposes,
        public PaginationDto $pagination,
    ) {
    }
}
