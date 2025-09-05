<?php

namespace App\Infrastructure\Service\FilterCriteriaApply\CriteriaApplicators;

use App\Aplication\CriteriaFilters\PaginationDto;
use Doctrine\DBAL\Query\QueryBuilder;

class PaginationCriteriaApplier implements CriterionApplierInterface
{
    public function apply(QueryBuilder $qb, string $alias, string $field, object $criterion, int $countWhere): int
    {
        if ($criterion instanceof PaginationDto) {
            if ($criterion->paginationEnabled) {
                if (null !== $criterion->offset) {
                    $qb->setFirstResult($criterion->offset);
                }
                if (null !== $criterion->limit) {
                    $qb->setMaxResults($criterion->limit);
                }

                if (1 === $criterion->isDelete) {
                    $qb->andWhere("$alias.$field.isDelete = :isDelete")
                        ->setParameter('isDelete', 1);
                }
            }
        }

        return $countWhere;
    }
}
