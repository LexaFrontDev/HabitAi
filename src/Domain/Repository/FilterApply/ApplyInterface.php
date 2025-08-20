<?php

namespace App\Domain\Repository\FilterApply;

use Doctrine\DBAL\Query\QueryBuilder;

interface ApplyInterface
{
    public function applyCriteria(QueryBuilder $qb, object $criteriaDto, string $alias = 'u'): void;
}
