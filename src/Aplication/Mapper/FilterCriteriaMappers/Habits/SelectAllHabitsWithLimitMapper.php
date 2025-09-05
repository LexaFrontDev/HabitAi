<?php

namespace App\Aplication\Mapper\FilterCriteriaMappers\Habits;

use App\Aplication\CriteriaFilters\EntityFindById;
use App\Aplication\CriteriaFilters\EntityJoinCriteria;
use App\Aplication\CriteriaFilters\JoinType;
use App\Aplication\CriteriaFilters\PaginationDto;
use App\Aplication\Dto\ConditionsDto\OnConditionDto;
use App\Aplication\Dto\FiltersWithCriterias\Habits\SelectAllHabitsWithLimit;

class SelectAllHabitsWithLimitMapper
{
    public function toDto(int $userId, int $limit = 50, int $offset = 0): SelectAllHabitsWithLimit
    {
        return new SelectAllHabitsWithLimit(
            user_id: new EntityFindById(id: $userId),
            habits_data_juntion: new EntityJoinCriteria(
                'habits_data_juntion',
                'hdj',
                'data_type',
                JoinType::INNER,
                onCondition: [new OnConditionDto(expr: 'hdj.habits_id = h.id', type: 'AND')]
            ),
            date_daily: new EntityJoinCriteria(
                nameTable: 'date_daily',
                nameAlias: 'dd',
                select: '*',
                joinType: JoinType::LEFT,
                onCondition: [new OnConditionDto(expr: 'hdj.data_id = dd.id AND hdj.data_type = :data_type', type: 'AND')],
                paramsJoin: ['data_type' => 'daily']
            ),
            date_weekly: new EntityJoinCriteria(
                nameTable: 'date_weekly',
                nameAlias: 'dw',
                select: '*',
                joinType: JoinType::LEFT,
                onCondition: [new OnConditionDto(expr: 'hdj.data_id = dw.id AND hdj.data_type = :data_type', type: 'AND')],
                paramsJoin: ['data_type' => 'weekly']
            ),
            date_repeat_per_month: new EntityJoinCriteria(
                nameTable: 'date_repeat_per_month',
                nameAlias: 'dr',
                select: '*',
                joinType: JoinType::LEFT,
                onCondition: [new OnConditionDto(expr: 'hdj.data_id = dr.id AND hdj.data_type = :data_type', type: 'AND')],
                paramsJoin: ['data_type' => 'repeat']
            ),
            purposes: new EntityJoinCriteria(
                nameTable: 'purposes',
                nameAlias: 'p',
                select: '*, p.count AS count_purposes',
                joinType: JoinType::LEFT,
                onCondition: [new OnConditionDto(expr: 'p.habits_id = h.id', type: 'AND')]
            ),
            habits_history: new EntityJoinCriteria(
                nameTable: 'habits_history',
                nameAlias: 'hh',
                select: '*, hh.id AS habit_history_id',
                joinType: JoinType::LEFT,
                onCondition: [
                    new OnConditionDto(expr: 'hh.habits_id = h.id AND hh.user_id = :user_id', type: 'AND'),
                    new OnConditionDto(expr: 'DATE(hh.recorded_at) = CURRENT_DATE', type: 'AND'),
                ],
                paramsJoin: ['user_id' => $userId]
            ),
            pagination: new PaginationDto(
                limit: $limit,
                offset: $offset,
            )
        );
    }
}
