<?php

namespace App\Infrastructure\Service\FilterCriteriaApply\CriteriaApplicators;

use App\Aplication\CriteriaFilters\EntityFindByDate;
use App\Infrastructure\Service\FilterCriteriaApply\CriterionApplier;
use Doctrine\DBAL\Query\QueryBuilder;
use Psr\Log\LoggerInterface;

class DateCriterionApplier implements CriterionApplierInterface
{
    public function __construct(
        private CriterionApplier $applier,
        private LoggerInterface $logger,
    ) {
    }

    public function apply(QueryBuilder $qb, string $alias, string $field, object $criterion, int $countWhere): int
    {
        if ($criterion instanceof  EntityFindByDate) {
            if (!empty($criterion->YmdDate)) {
                try {
                    $date = new \DateTimeImmutable($criterion->YmdDate);
                    $this->applier->addCondition($qb, "$alias.$field = :{$field}_date", "{$field}_date", $date->format('Y-m-d'), $countWhere);
                    ++$countWhere;
                } catch (\Exception $e) {
                    $this->logger->error($e->getMessage());
                }
            }

            if (!empty($criterion->YmdTime)) {
                try {
                    $dateTime = new \DateTimeImmutable($criterion->YmdTime);
                    $this->applier->addCondition($qb, "$alias.$field = :{$field}_datetime", "{$field}_datetime", $dateTime->format('Y-m-d H:i:s'), $countWhere);
                    ++$countWhere;
                } catch (\Exception $e) {
                    $this->logger->error($e->getMessage());
                }
            }

            if (!empty($criterion->YmdTimeStamp)) {
                try {
                    $dateFromTs = (new \DateTimeImmutable())->setTimestamp((int) $criterion->YmdTimeStamp);
                    $this->applier->addCondition($qb, "$alias.$field = :{$field}_ts", "{$field}_ts", $dateFromTs->format('Y-m-d H:i:s'), $countWhere);
                    ++$countWhere;
                } catch (\Exception $e) {
                    $this->logger->error($e->getMessage());
                }
            }
        }

        return $countWhere;
    }
}
