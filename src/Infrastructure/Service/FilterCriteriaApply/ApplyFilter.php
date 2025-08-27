<?php

namespace App\Infrastructure\Service\FilterCriteriaApply;

use App\Aplication\CriteriaFilters\EntityFindByDate;
use App\Aplication\CriteriaFilters\EntityFindById;
use App\Aplication\CriteriaFilters\EntityFindByLike;
use App\Aplication\CriteriaFilters\EntityFindByString;
use App\Aplication\CriteriaFilters\EntityJoinCriteria;
use App\Aplication\CriteriaFilters\PaginationDto;
use App\Aplication\Dto\ConditionsDto\OnConditionDto;
use App\Domain\Service\QueriFilterInterface\FilterInterface;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Query\Expression\CompositeExpression;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class ApplyFilter implements FilterInterface
{
    private QueryBuilder $qb;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private LoggerInterface $logger,
    ) {
    }

    /**
     * @return $this
     */
    public function initFilter(object $criteriasDto, string $tableName, string $alias = 'u', string $select = '*'): self
    {
        $this->qb = $this->entityManager->getConnection()->createQueryBuilder()
            ->select($select)
            ->from($tableName, $alias);

        $countWhere = 0;

        foreach ((array) $criteriasDto as $field => $criterion) {
            $countWhere = $this->applyCriteria($criterion, $alias, $field, $countWhere);
            $this->applyJoin($criterion, $alias);
        }

        return $this;
    }

    /**
     * @return mixed[]
     *
     * @throws Exception
     */
    public function getList(): array
    {
        return $this->qb->executeQuery()->fetchAllAssociative();
    }

    /**
     * @return mixed[]|null
     *
     * @throws Exception
     */
    public function getOne(): ?array
    {
        return $this->qb->executeQuery()->fetchAssociative() ?: null;
    }

    public function getSql(): string
    {
        return $this->qb->getSQL();
    }

    /**
     * @throws Exception
     */
    public function getCount(): int
    {
        $rows = $this->qb->executeQuery()->fetchAllAssociative();

        return \count($rows);
    }

    /**
     * @return mixed[]
     */
    public function getParameter(): array
    {
        return $this->qb->getParameters();
    }

    private function applyCriteria(object $criterion, string $alias, string $field, int $countWhere): int
    {
        if ($criterion instanceof EntityFindById) {
            if (null !== $criterion->id) {
                $this->addCondition("$alias.$field = :$field", $field, $criterion->id, $countWhere);
                ++$countWhere;
            }
            if (!empty($criterion->in)) {
                $this->addCondition("$alias.$field IN (:in_$field)", "in_$field", $criterion->in, $countWhere);
                ++$countWhere;
            }
            if (!empty($criterion->notIn)) {
                $this->addCondition("$alias.$field NOT IN (:notin_$field)", "notin_$field", $criterion->notIn, $countWhere);
                ++$countWhere;
            }
        }

        if ($criterion instanceof EntityFindByLike) {
            if (!empty($criterion->like)) {
                $this->addLike($alias, $field, $criterion->like, $countWhere);
                ++$countWhere;
            }
            if (!empty($criterion->notLike)) {
                $this->addNotLike($alias, $field, $criterion->notLike, $countWhere);
                ++$countWhere;
            }
        }


        if ($criterion instanceof EntityFindByString) {
            if (!empty($criterion->equal)) {
                $this->addCondition("$alias.$field = :$field", $field, $criterion->equal, $countWhere);
                ++$countWhere;
            }

            if (!empty($criterion->anyOf) && is_array($criterion->anyOf)) {
                $expr = $this->qb->expr();
                $orX = null;
                foreach ($criterion->anyOf as $i => $val) {
                    $paramName = "{$field}_any_$i";
                    $this->qb->setParameter($paramName, $val);
                    $current = "$alias.$field = :$paramName";
                    $orX = $orX ? $expr->orX($orX, $current) : $current;
                }
                $countWhere > 0 ? $this->qb->andWhere($orX) : $this->qb->where($orX);
                ++$countWhere;
            }

            if (!empty($criterion->in) && is_array($criterion->in)) {
                $this->addCondition("$alias.$field IN (:in_$field)", "in_$field", $criterion->in, $countWhere);
                ++$countWhere;
            }

        }


        if ($criterion instanceof  EntityFindByDate) {
            if (!empty($criterion->YmdDate)) {
                try {
                    $date = new \DateTimeImmutable($criterion->YmdDate);
                    $this->addCondition("$alias.$field = :{$field}_date", "{$field}_date", $date->format('Y-m-d'), $countWhere);
                    ++$countWhere;
                } catch (\Exception $e) {
                    $this->logger->error($e->getMessage());
                }
            }

            if (!empty($criterion->YmdTime)) {
                try {
                    $dateTime = new \DateTimeImmutable($criterion->YmdTime);
                    $this->addCondition("$alias.$field = :{$field}_datetime", "{$field}_datetime", $dateTime->format('Y-m-d H:i:s'), $countWhere);
                    ++$countWhere;
                } catch (\Exception $e) {
                    $this->logger->error($e->getMessage());
                }
            }

            if (!empty($criterion->YmdTimeStamp)) {
                try {
                    $dateFromTs = (new \DateTimeImmutable())->setTimestamp((int) $criterion->YmdTimeStamp);
                    $this->addCondition("$alias.$field = :{$field}_ts", "{$field}_ts", $dateFromTs->format('Y-m-d H:i:s'), $countWhere);
                    ++$countWhere;
                } catch (\Exception $e) {
                    $this->logger->error($e->getMessage());
                }
            }
        }


        if ($criterion instanceof PaginationDto) {
            if ($criterion->paginationEnabled) {
                if (null !== $criterion->offset) {
                    $this->qb->setFirstResult($criterion->offset);
                }
                if (null !== $criterion->limit) {
                    $this->qb->setMaxResults($criterion->limit);
                }
            }
        }


        return $countWhere;
    }

    private function applyJoin(object $criterion, string $alias): void
    {
        if (!$criterion instanceof EntityJoinCriteria) {
            return;
        }

        $onExpr = $criterion->onCondition ? $this->buildOnCondition($criterion->onCondition) : '1=1';

        match (strtoupper($criterion->joinType)) {
            'LEFT' => $this->qb->leftJoin($alias, $criterion->nameTable, $criterion->nameAlias, $onExpr),
            'RIGHT' => $this->qb->rightJoin($alias, $criterion->nameTable, $criterion->nameAlias, $onExpr),
            default => $this->qb->innerJoin($alias, $criterion->nameTable, $criterion->nameAlias, $onExpr),
        };

        foreach ((array) $criterion->select as $f) {
            $this->qb->addSelect("{$criterion->nameAlias}.$f");
        }
    }

    /**
     * @param OnConditionDto[] $conditions
     */
    private function buildOnCondition(array $conditions): CompositeExpression|string
    {
        $expr = $this->qb->expr();
        $onExpr = null;

        foreach ($conditions as $cond) {
            $current = $cond->expr;
            $onExpr = null === $onExpr
                ? $current
                : ('OR' === strtoupper($cond->type) ? $expr->orX($onExpr, $current) : $expr->andX($onExpr, $current));
        }

        return $onExpr ?? '1=1';
    }

    private function addCondition(string $expr, string $paramName, mixed $paramValue, int $countWhere): void
    {
        $countWhere > 0 ? $this->qb->andWhere($expr) : $this->qb->where($expr);

        $paramType = is_array($paramValue) ? \Doctrine\DBAL\Connection::PARAM_INT_ARRAY : null;
        $this->qb->setParameter($paramName, $paramValue, $paramType);
    }

    private function addLike(string $alias, string $field, string $value, int $countWhere): void
    {
        $condition = $this->qb->expr()->like("$alias.$field", ":$field");
        $countWhere > 0 ? $this->qb->andWhere($condition) : $this->qb->where($condition);
        $this->qb->setParameter($field, "%$value%");
    }

    private function addNotLike(string $alias, string $field, string $value, int $countWhere): void
    {
        $condition = $this->qb->expr()->notLike("$alias.$field", ":not_$field");
        $countWhere > 0 ? $this->qb->andWhere($condition) : $this->qb->where($condition);
        $this->qb->setParameter("not_$field", "%$value%");
    }
}
