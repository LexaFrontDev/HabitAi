<?php

namespace App\Aplication\UseCase\DatesUseCases;

use App\Aplication\Dto\DatesDto\DatesDailyForSaveDto;
use App\Aplication\Dto\DatesDto\ReqDataJunction;
use App\Domain\Repository\DatesRepository\DataJunctionRepositoryInterface;
use App\Domain\Repository\DatesRepository\DatesDailyRepositoryInterface;
use App\Domain\Repository\DatesWeekly\DatesWeeklyRepositoryInterface;
use App\Domain\Repository\DayesRepeatRepository\DatesRepeatRepositoryInterface;
use App\Domain\Repository\Habits\HabitsRepositoryInterface;
use App\Domain\Repository\PurposeRepository\PurposeRepositoryInterface;
use App\Domain\Service\JwtServicesInterface;
use App\Domain\Service\Tokens\AuthTokenServiceInterface;

class DatesCommandUseCase
{

    public function __construct(
        private DatesWeeklyRepositoryInterface $datesWeeklyRepository,
        private DatesDailyRepositoryInterface $datesDailyRepository,
        private DatesRepeatRepositoryInterface $datesRepeatRepository,
        private DataJunctionRepositoryInterface $dataJunctionRepository,
        private AuthTokenServiceInterface $authTokenService,
        private JwtServicesInterface $jwtServices,
    ){}


    public function getDatesDto(string $datesType, array $datesArray): DatesDailyForSaveDto|array|bool
    {
        return match ($datesType) {
            'daily' => $this->dailyDatesDto($datesArray),
            'weekly' => $this->weeklyDatesDto($datesArray),
            'repeat' => $this->repeatDatesDto($datesArray),
            default => false,
        };
    }






    public function saveDataHabits(string $datesType, array $datesArray, int $habitsId): bool|int
    {
        $isDates = $this->getDatesDto($datesType, $datesArray);
        if (empty($isDates)) return false;
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

        if (empty($isSucces)) return false;
        $datesJunction = new ReqDataJunction($habitsId, $isSucces, $datesType);
        return $this->dataJunctionRepository->saveDateJunction($datesJunction);
    }



    public function dailyDatesDto(array $datesArray): DatesDailyForSaveDto
    {
        return new DatesDailyForSaveDto(
            (bool)($datesArray['mon'] ?? false),
            (bool)($datesArray['tue'] ?? false),
            (bool)($datesArray['wed'] ?? false),
            (bool)($datesArray['thu'] ?? false),
            (bool)($datesArray['fri'] ?? false),
            (bool)($datesArray['sat'] ?? false),
            (bool)($datesArray['sun'] ?? false),
        );
    }



    public function WeeklyDatesDto(array $datesArray): array|false
    {
        if (!isset($datesArray['count'])) {
            return false;
        }
        return [
            'count' => (int)$datesArray['count']
        ];
    }


    public function repeatDatesDto(array $datesArray): array|bool
    {
        if (!isset($datesArray['day'])) {
            return false;
        }
        return [
            'day' => (int)$datesArray['day']
        ];
    }


}