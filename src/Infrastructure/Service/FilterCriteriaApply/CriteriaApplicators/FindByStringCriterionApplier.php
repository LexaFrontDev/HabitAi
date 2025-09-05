<?php

namespace App\Infrastructure\Service\FilterCriteriaApply\CriteriaApplicators;

use App\Aplication\CriteriaFilters\EntityFindByString;
use App\Infrastructure\Service\FilterCriteriaApply\CriterionApplier;
use Doctrine\DBAL\Query\QueryBuilder;

class FindByStringCriterionApplier implements CriterionApplierInterface
{
    public function __construct(
        private CriterionApplier $applier,
    ) {
    }

    public function apply(QueryBuilder $qb, string $alias, string $field, object $criterion, int $countWhere): int
    {
        if ($criterion instanceof EntityFindByString) {
            if (!empty($criterion->equal)) {
                $this->applier->addCondition($qb, "$alias.$field = :$field", $field, $criterion->equal, $countWhere);
                ++$countWhere;
            }

            if (!empty($criterion->anyOf) && is_array($criterion->anyOf)) {
                $expr = $qb->expr();
                $orX = null;
                foreach ($criterion->anyOf as $i => $val) {
                    $paramName = "{$field}_any_$i";
                    $qb->setParameter($paramName, $val);
                    $current = "$alias.$field = :$paramName";
                    $orX = $orX ? $expr->orX($orX, $current) : $current;
                }
                $countWhere > 0 ? $qb->andWhere($orX) : $qb->where($orX);
                ++$countWhere;
            }

            if (!empty($criterion->in) && is_array($criterion->in)) {
                $this->applier->addCondition($qb, "$alias.$field IN (:in_$field)", "in_$field", $criterion->in, $countWhere);
                ++$countWhere;
            }

        }

        return $countWhere;
    }
}
