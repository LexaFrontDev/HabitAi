<?php

namespace App\Aplication\Mapper\FilterCriteriaMappers\Habits;

use App\Aplication\CriteriaFilters\EntityFindById;
use App\Aplication\CriteriaFilters\EntityJoinCriteria;
use App\Aplication\CriteriaFilters\JoinType;
use App\Aplication\CriteriaFilters\PaginationDto;
use App\Aplication\Dto\ConditionsDto\OnConditionDto;
use App\Aplication\Dto\FiltersWithCriterias\Habits\FilterSelectHabitsStatisticAll;

class CriteriaSelectHabitsStatisticAll
{
    public function mapToDto(int $userId, int $limit = 5, int $offset = 0): FilterSelectHabitsStatisticAll
    {
        return new FilterSelectHabitsStatisticAll(
            user_id: new EntityFindById(id: $userId),
            join_habits: new EntityJoinCriteria(
                nameTable: 'habits',
                nameAlias: 'h',
                select: 'title',
                joinType: JoinType::INNER,
                onCondition: [
                    new OnConditionDto(
                        left: 'h.id',
                        operator: '=',
                        right: 'hh.habits_id',
                        type: 'AND'
                    ),
                ]
            ),
            pagination: new PaginationDto(limit: $limit, offset: $offset, isDelete: 1),
        );
    }
}
