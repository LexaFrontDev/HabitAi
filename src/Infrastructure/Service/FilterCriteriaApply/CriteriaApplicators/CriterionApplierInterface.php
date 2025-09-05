<?php

namespace App\Infrastructure\Service\FilterCriteriaApply\CriteriaApplicators;

use Doctrine\DBAL\Query\QueryBuilder;

interface CriterionApplierInterface
{
    public function apply(QueryBuilder $qb, string $alias, string $field, object $criterion, int $countWhere): int;
}
