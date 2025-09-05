<?php

namespace App\Infrastructure\Service\FilterCriteriaApply;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Query\QueryBuilder;

final class CriterionApplier
{
    public function addCondition(QueryBuilder $qb, string $expr, string $paramName, mixed $paramValue, int $countWhere): void
    {
        $countWhere > 0 ? $qb->andWhere($expr) : $qb->where($expr);
        $paramType = null;

        if (is_array($paramValue)) {
            $paramType = ParameterType::INTEGER;
        }

        $qb->setParameter($paramName, $paramValue, $paramType);
    }
}
