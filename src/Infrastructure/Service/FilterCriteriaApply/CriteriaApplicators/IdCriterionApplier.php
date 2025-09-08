<?php

namespace App\Infrastructure\Service\FilterCriteriaApply\CriteriaApplicators;

use App\Aplication\CriteriaFilters\EntityFindById;
use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Query\QueryBuilder;

class IdCriterionApplier implements CriterionApplierInterface
{
    public function apply(QueryBuilder $qb, string $alias, string $field, object $criterion, int $countWhere): int
    {
        if ($criterion instanceof EntityFindById) {
            // === id ===
            if (null !== $criterion->id) {
                $expr = "$alias.$field = :$field";
                $countWhere > 0 ? $qb->andWhere($expr) : $qb->where($expr);

                $qb->setParameter($field, $criterion->id);
                ++$countWhere;
            }

            // === in ===
            if (!empty($criterion->in)) {
                $paramName = "in_$field";
                $expr = "$alias.$field IN (:$paramName)";
                $countWhere > 0 ? $qb->andWhere($expr) : $qb->where($expr);

                $qb->setParameter($paramName, $criterion->in, ParameterType::INTEGER);
                ++$countWhere;
            }

            // === not in ===
            if (!empty($criterion->notIn)) {
                $paramName = "notin_$field";
                $expr = "$alias.$field NOT IN (:$paramName)";
                $countWhere > 0 ? $qb->andWhere($expr) : $qb->where($expr);
                $qb->setParameter($paramName, $criterion->notIn, ParameterType::INTEGER);
                ++$countWhere;
            }
        }

        return $countWhere;
    }
}
