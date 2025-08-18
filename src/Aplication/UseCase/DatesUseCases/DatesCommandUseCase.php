<?php

namespace App\Aplication\UseCase\DatesUseCases;

use App\Aplication\Dto\DatesDto\DatesDailyForSaveDto;
use App\Aplication\Dto\DatesDto\ReqDataJunction;
use App\Domain\Repository\Dates\DataJunctionRepositoryInterface;
use App\Domain\Repository\Dates\DatesDailyRepositoryInterface;
use App\Domain\Repository\DatesWeekly\DatesWeeklyRepositoryInterface;
use App\Domain\Repository\DayesRepeat\DatesRepeatRepositoryInterface;

class DatesCommandUseCase
{
    public function __construct(
        private DatesWeeklyRepositoryInterface $datesWeeklyRepository,
        private DatesDailyRepositoryInterface $datesDailyRepository,
        private DatesRepeatRepositoryInterface $datesRepeatRepository,
        private DataJunctionRepositoryInterface $dataJunctionRepository,
    ) {
    }

    /**
     * @param array<int, mixed> $datesArray
     *
     * @return DatesDailyForSaveDto|array{count:int}|array{day:int}|false
     */
    public function getDatesDto(string $datesType, array $datesArray): DatesDailyForSaveDto|array|bool
    {
        return match ($datesType) {
            'daily' => $this->dailyDatesDto($datesArray),
            'weekly' => $this->weeklyDatesDto($datesArray),
            'repeat' => $this->repeatDatesDto($datesArray),
            default => false,
        };
    }

    /**
     * @param array<int, mixed> $datesArray
     *
     * @return int|false
     */
    public function saveDataHabits(string $datesType, array $datesArray, int $habitsId): bool|int
    {
        $isDates = $this->getDatesDto($datesType, $datesArray);
        if (empty($isDates)) {
            return false;
        }
        $isSucces = null;

        if ($isDates instanceof DatesDailyForSaveDto) {
            $isSucces = $this->datesDailyRepository->saveDates($isDates);
        } elseif (isset($isDates['count'])) {
            $isSucces = $this->datesWeeklyRepository->saveDatesWeekly($isDates['count']);
        } elseif (isset($isDates['day'])) {
            $isSucces = $this->datesRepeatRepository->saveDatesRepeat($isDates['day']);
        } else {
            return false;
        }

        if (empty($isSucces) || !is_int($isSucces)) {
            return false;
        }
        $datesJunction = new ReqDataJunction($habitsId, $isSucces, $datesType);

        return $this->dataJunctionRepository->saveDateJunction($datesJunction);
    }

    /**
     * @param array<int, mixed> $dates
     */
    public function saveOrUpdateDatesByHabitsId(int $habitsId, array $dates, string $types): bool
    {
        $dto = $this->getDatesDto($types, $dates);
        $junction = $this->dataJunctionRepository->getDateJunctionByHabitsId($habitsId);

        if ($junction) {
            $oldType = $junction->getDataType();
            $oldDataId = $junction->getDataId();

            if ($oldType !== $types) {
                match ($oldType) {
                    'daily' => $this->datesDailyRepository->deleteDatesDaily($oldDataId),
                    'weekly' => $this->datesWeeklyRepository->deleteDatesWeekly($oldDataId),
                    'repeat' => $this->datesRepeatRepository->deleteDatesRepeat($oldDataId),
                    default => null,
                };

                $newDataId = match (true) {
                    $dto instanceof DatesDailyForSaveDto => $this->datesDailyRepository->saveDates($dto),
                    isset($dto['count']) => $this->datesWeeklyRepository->saveDatesWeekly($dto['count']),
                    isset($dto['day']) => $this->datesRepeatRepository->saveDatesRepeat($dto['day']),
                    default => null,
                };

                if (empty($newDataId) || !is_int($newDataId)) {
                    return false;
                }

                $this->dataJunctionRepository->updateDataTypeAndId($junction->getId(), $habitsId, $types, $newDataId);

                return true;
            }

            return match (true) {
                $dto instanceof DatesDailyForSaveDto => $this->datesDailyRepository->updateDates($oldDataId, $dto),
                isset($dto['count']) => $this->datesWeeklyRepository->updateDatesWeekly($oldDataId, $dto['count']),
                isset($dto['day']) => $this->datesRepeatRepository->updateDatesRepeat($oldDataId, $dto['day']),
                default => false,
            };
        }

        $newDataId = match (true) {
            $dto instanceof DatesDailyForSaveDto => $this->datesDailyRepository->saveDates($dto),
            isset($dto['count']) => $this->datesWeeklyRepository->saveDatesWeekly($dto['count']),
            isset($dto['day']) => $this->datesRepeatRepository->saveDatesRepeat($dto['day']),
            default => null,
        };

        if (!$newDataId || !is_int($newDataId)) {
            return false;
        }

        $this->dataJunctionRepository->createJunction($habitsId, $types, $newDataId);

        return true;
    }

    /**
     * @param array<int, mixed> $datesArray
     */
    public function dailyDatesDto(array $datesArray): DatesDailyForSaveDto
    {
        return new DatesDailyForSaveDto(
            (bool) ($datesArray['mon'] ?? false),
            (bool) ($datesArray['tue'] ?? false),
            (bool) ($datesArray['wed'] ?? false),
            (bool) ($datesArray['thu'] ?? false),
            (bool) ($datesArray['fri'] ?? false),
            (bool) ($datesArray['sat'] ?? false),
            (bool) ($datesArray['sun'] ?? false),
        );
    }

    /**
     * @param array<int, mixed> $datesArray
     *
     * @return array{count:int}|false
     */
    public function WeeklyDatesDto(array $datesArray): array|false
    {
        if (!isset($datesArray['count'])) {
            return false;
        }

        return [
            'count' => (int) $datesArray['count'],
        ];
    }

    /**
     * @param array<int, mixed> $datesArray
     *
     * @return array{day:int}|false
     */
    public function repeatDatesDto(array $datesArray): array|bool
    {
        if (!isset($datesArray['day'])) {
            return false;
        }

        return [
            'day' => (int) $datesArray['day'],
        ];
    }
}
