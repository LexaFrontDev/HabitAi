<?php

namespace App\Aplication\Dto\FiltersWithCriterias\Habits;

use App\Aplication\CriteriaFilters\EntityFindById;
use App\Aplication\CriteriaFilters\EntityJoinCriteria;
use App\Aplication\CriteriaFilters\PaginationDto;

final class SelectAllHabitsWithLimit
{
    public function __construct(
        public readonly EntityFindById $user_id,
        public readonly EntityJoinCriteria $habits_data_juntion,
        public readonly EntityJoinCriteria $date_daily,
        public readonly EntityJoinCriteria $date_weekly,
        public readonly EntityJoinCriteria $date_repeat_per_month,
        public readonly EntityJoinCriteria $purposes,
        public readonly EntityJoinCriteria $habits_history,
        public readonly PaginationDto $pagination,
    ) {
    }
}
