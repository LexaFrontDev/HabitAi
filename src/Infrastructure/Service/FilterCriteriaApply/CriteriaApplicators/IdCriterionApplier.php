<?php

namespace App\Infrastructure\Service\FilterCriteriaApply\CriteriaApplicators;

use App\Aplication\CriteriaFilters\EntityFindById;
use App\Infrastructure\Service\FilterCriteriaApply\CriterionApplier;
use Doctrine\DBAL\Query\QueryBuilder;

class IdCriterionApplier implements CriterionApplierInterface
{
    public function __construct(
        private CriterionApplier $criterionApplier,
    ) {
    }

    public function apply(QueryBuilder $qb, string $alias, string $field, object $criterion, int $countWhere): int
    {
        if ($criterion instanceof EntityFindById) {
            if (null !== $criterion->id) {
                $this->criterionApplier->addCondition($qb, "$alias.$field = :$field", $field, $criterion->id, $countWhere);
                ++$countWhere;
            }
            if (!empty($criterion->in)) {
                $this->criterionApplier->addCondition($qb, "$alias.$field IN (:in_$field)", "in_$field", $criterion->in, $countWhere);
                ++$countWhere;
            }
            if (!empty($criterion->notIn)) {
                $this->criterionApplier->addCondition($qb, "$alias.$field NOT IN (:notin_$field)", "notin_$field", $criterion->notIn, $countWhere);
                ++$countWhere;
            }
        }

        return $countWhere;
    }
}
