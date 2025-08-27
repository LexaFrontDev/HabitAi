<?php

namespace App\Aplication\Mapper\FilterCriteriaMappers\StatisticMapper;

use App\Aplication\CriteriaFilters\EntityFindById;
use App\Aplication\CriteriaFilters\EntityJoinCriteria;
use App\Aplication\CriteriaFilters\JoinType;
use App\Aplication\CriteriaFilters\PaginationDto;
use App\Aplication\Dto\ConditionsDto\OnConditionDto;
use App\Aplication\Dto\FiltersWithCriterias\Habits\FilterSelectStatisticAll;

class CriteriaSelectStatisticAllMapper
{
    public function mapToDto(int $userId, int $habitId, int $limit = 5, int $offset = 0): ?FilterSelectStatisticAll
    {
        return new FilterSelectStatisticAll(
            user_id: new EntityFindById(id: $userId),
            habits_id: new EntityFindById(id: $habitId),
            join_habits: new EntityJoinCriteria(
                nameTable: 'habits',
                nameAlias: 'h',
                select: 'title',
                joinType: JoinType::INNER,
                onCondition: [new OnConditionDto(expr: 'h.id = hh.habits_id', type: 'and')]
            ),
            pagination: new PaginationDto(limit: $limit, offset: $offset, isDelete: 1),
        );
    }

    /**
     * @return array{
     *      user_id: int|null,
     *      habit_id: int|null,
     *      join_habits: EntityJoinCriteria|null,
     *      limit: int,
     *      offset: int
     * }
     */
    public function toArray(FilterSelectStatisticAll $dto): array
    {
        return [
            'user_id' => $dto->user_id->id ?? null,
            'habit_id' => $dto->habit_id->id ?? null,
            'join_habits' => $dto->join_habits ?? null,
            'limit' => $dto->pagination->limit ?? 5,
            'offset' => $dto->pagination->offset ?? 0,
        ];
    }
}
