<?php

namespace App\Aplication\Mapper\FilterCriteriaMappers\Matric;

use App\Aplication\CriteriaFilters\EntityFindById;
use App\Aplication\CriteriaFilters\EntityJoinCriteria;
use App\Aplication\CriteriaFilters\JoinType;
use App\Aplication\CriteriaFilters\PaginationDto;
use App\Aplication\Dto\ConditionsDto\OnConditionDto;
use App\Aplication\Dto\FiltersWithCriterias\Matric\SelectAllMatrics;

class SelectAllMatricMapper
{
    public function toDto(int $userId, int $limit = 50, int $offset = 0): SelectAllMatrics
    {
        return new SelectAllMatrics(
            user_id: new EntityFindById(
                id: $userId
            ),
            Tasks: new EntityJoinCriteria(
                nameTable: 'tasks',
                nameAlias: 't',
                select: '*',
                joinType: JoinType::INNER,
                onCondition: [new OnConditionDto(expr: 't.id = mj.task_id', type: 'AND')]
            ),
            paginationDto: new PaginationDto(
                limit: $limit,
                offset: $offset
            )
        );
    }
}
