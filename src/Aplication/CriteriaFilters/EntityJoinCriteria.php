<?php

namespace App\Aplication\CriteriaFilters;

use App\Aplication\Dto\ConditionsDto\OnConditionDto;

final class EntityJoinCriteria
{
    /**
     * @param string[]|string $select
     * @param OnConditionDto[]|null $onCondition
     * @param array<string, mixed> $paramsJoin
     */
    public function __construct(
        public string $nameTable,
        public string $nameAlias,
        public string|array $select = [],
        public string $joinType = JoinType::INNER,
        public ?array $onCondition = null,
        public array $paramsJoin = [],
    ) {
    }
}
