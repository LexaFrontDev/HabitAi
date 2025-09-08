<?php

namespace App\Infrastructure\Service\FilterCriteriaApply\CriteriaApplicators;

use App\Aplication\CriteriaFilters\OrderByDto;
use Doctrine\DBAL\Query\QueryBuilder;

final class OrderByCriterionApplier implements CriterionApplierInterface
{
    public function apply(QueryBuilder $qb, string $alias, string $field, object $criterion, int $countWhere): int
    {
        if ($criterion instanceof OrderByDto) {
            $qb->addOrderBy($criterion->field, $criterion->direction);
        }

        return $countWhere;
    }
}
