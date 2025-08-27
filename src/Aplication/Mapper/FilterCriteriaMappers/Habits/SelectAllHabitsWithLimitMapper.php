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
                onCondition: [new OnConditionDto(expr: "hdj.data_id = dd.id AND hdj.data_type = 'daily'", type: 'AND')]
            ),
            date_weekly: new EntityJoinCriteria(
                nameTable: 'date_weekly',
                nameAlias: 'dw',
                select: '*',
                joinType: JoinType::LEFT,
                onCondition: [new OnConditionDto(expr: "hdj.data_id = dw.id AND hdj.data_type = 'weekly'", type: 'AND')]
            ),
            date_repeat_per_month: new EntityJoinCriteria(
                nameTable: 'date_repeat_per_month',
                nameAlias: 'dr',
                select: '*',
                joinType: JoinType::LEFT,
                onCondition: [new OnConditionDto(expr: "hdj.data_id = dr.id AND hdj.data_type = 'repeat'", type: 'AND')]
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
                    new OnConditionDto(expr: "hh.habits_id = h.id AND hh.user_id = $userId", type: 'AND'),
                    new OnConditionDto(expr: 'DATE(hh.recorded_at) = CURRENT_DATE', type: 'AND'),
                ]
            ),
            pagination: new PaginationDto(
                limit: $limit,
                offset: $offset,
            )
        );
    }
}
