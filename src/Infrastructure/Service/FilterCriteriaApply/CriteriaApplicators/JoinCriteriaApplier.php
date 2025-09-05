<?php

namespace App\Infrastructure\Service\FilterCriteriaApply\CriteriaApplicators;

use App\Aplication\CriteriaFilters\EntityJoinCriteria;
use App\Aplication\Dto\ConditionsDto\OnConditionDto;
use Doctrine\DBAL\Query\Expression\CompositeExpression;
use Doctrine\DBAL\Query\QueryBuilder;

class JoinCriteriaApplier implements CriterionApplierInterface
{
    public function apply(QueryBuilder $qb, string $alias, string $field, object $criterion, int $countWhere): int
    {
        if (!$criterion instanceof EntityJoinCriteria) {
            return $countWhere;
        }

        $onExpr = $criterion->onCondition
            ? $this->buildOnCondition($qb, $criterion->onCondition, $criterion->paramsJoin)
            : '1=1';

        match (strtoupper($criterion->joinType)) {
            'LEFT' => $qb->leftJoin($alias, $criterion->nameTable, $criterion->nameAlias, $onExpr),
            'RIGHT' => $qb->rightJoin($alias, $criterion->nameTable, $criterion->nameAlias, $onExpr),
            default => $qb->innerJoin($alias, $criterion->nameTable, $criterion->nameAlias, $onExpr),
        };

        foreach ((array) $criterion->select as $f) {
            $qb->addSelect("{$criterion->nameAlias}.$f");
        }

        return $countWhere;
    }

    /**
     * @param OnConditionDto[] $conditions
     * @param array<string, mixed> $params
     */
    private function buildOnCondition(QueryBuilder $qb, array $conditions, array $params): CompositeExpression|string
    {
        $onExpr = null;

        foreach ($conditions as $cond) {
            if ($cond->isRawExpr()) {
                $current = $cond->expr;
            } else {
                if (null !== $cond->rightParam) {
                    $placeholder = ':'.$cond->rightParam;
                    $current = $qb->expr()->comparison($cond->left, $cond->operator ?? '=', $placeholder);

                    if (array_key_exists($cond->rightParam, $params)) {
                        $qb->setParameter($cond->rightParam, $params[$cond->rightParam]);
                    }
                } else {
                    $current = $qb->expr()->comparison($cond->left, $cond->operator ?? '=', $cond->right);
                }
            }

            if (null === $onExpr) {
                $onExpr = $current;
            } else {
                $onExpr = new CompositeExpression(
                    'OR' === strtoupper($cond->type)
                        ? CompositeExpression::TYPE_OR
                        : CompositeExpression::TYPE_AND,
                    [$onExpr, $current]
                );
            }
        }

        return $onExpr ?? '1=1';
    }
}
