<?php

namespace App\Aplication\UseCase\HabitsUseCase;

use App\Aplication\Dto\HabitsDtoUseCase\ReqHabitsDto;
use App\Aplication\Dto\HabitsDtoUseCase\ReqUpdateHabitsDto;
use App\Aplication\Dto\HabitsDtoUseCase\SaveHabitDto;
use App\Aplication\UseCase\DatesUseCases\DatesCommandUseCase;
use App\Domain\Repository\Dates\DatesDailyRepositoryInterface;
use App\Domain\Repository\DatesWeekly\DatesWeeklyRepositoryInterface;
use App\Domain\Repository\DayesRepeat\DatesRepeatRepositoryInterface;
use App\Domain\Repository\Habits\HabitsRepositoryInterface;
use App\Domain\Repository\Purpose\PurposeRepositoryInterface;
use App\Domain\Service\JwtServicesInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Aplication\Dto\PurposeDto\PurposeDto;

class CommandHabitsUseCase
{

    public function __construct(
        private HabitsRepositoryInterface $habitsRepository,
        private DatesCommandUseCase $datesCommandUseCase,
        private PurposeRepositoryInterface $purposeRepository,
        private JwtServicesInterface $jwtServices,
        private LoggerInterface $logger,
    ){}



    public function updateHabits(ReqUpdateHabitsDto $reqHabitsDto, Request $request): bool
    {
            $token = $this->jwtServices->getTokens($request);
            $userInfo = $this->jwtServices->getUserInfoFromToken($token['accessToken']);
            $userId = $userInfo->getUserId();

            $habitDto = new SaveHabitDto(
                $reqHabitsDto->titleHabit,
                $reqHabitsDto->quote,
                $reqHabitsDto->goalInDays,
                $reqHabitsDto->beginDate,
                $reqHabitsDto->notificationDate,
                '',
                $userId
            );

            $updated = $this->habitsRepository->updateHabitById($reqHabitsDto->habitId, $userId, $habitDto);
            if (!$updated) {
                return false;
            }

            $purposeDto = new PurposeDto(
                habitsId: $reqHabitsDto->habitId,
                type: $reqHabitsDto->purposeType,
                count: $reqHabitsDto->purposeCount,
                checkManually: $reqHabitsDto->checkManually,
                autoCount: $reqHabitsDto->autoCount,
                checkAuto: $reqHabitsDto->checkAuto,
                checkClose: $reqHabitsDto->checkClose
            );

            $this->purposeRepository->updatePurposeByHabitId($purposeDto);

            $this->datesCommandUseCase->saveOrUpdateDatesByHabitsId(
                $reqHabitsDto->habitId,
                $reqHabitsDto->date,
                $reqHabitsDto->datesType
            );

            return true;
    }


    public function saveHabits(ReqHabitsDto $reqHabitsDto, Request $request): bool
    {
        try {
            $token = $this->jwtServices->getTokens($request);
            $userId = $this->jwtServices->getUserInfoFromToken($token['accessToken']);
            $userId = $userId->getUserId();

            $habitsDto = new SaveHabitDto(
                $reqHabitsDto->getTitleHabit(),
                $reqHabitsDto->getQuote(),
                $reqHabitsDto->getGoalInDays(),
                $reqHabitsDto->getBeginDate(),
                $reqHabitsDto->getNotificationDate(),
                '',
                $userId
            );

            $habitsId = $this->habitsRepository->saveHabits($habitsDto);
            if (empty($habitsId)) {
                return false;
            }

            $purposeData = new PurposeDto(
                $habitsId,
                $reqHabitsDto->getPurposeType(),
                $reqHabitsDto->getPurposeCount(),
                $reqHabitsDto->isCheckManually(),
                $reqHabitsDto->getautoCount(),
                $reqHabitsDto->isCheckAuto(),
                $reqHabitsDto->isCheckClose(),
            );
            $isSavePurpose = $this->purposeRepository->savePurpose($purposeData);
            if (empty($isSavePurpose)) {
                return false;
            }

            $isResult = $this->datesCommandUseCase->saveDataHabits(
                $reqHabitsDto->getDatesType(),
                $reqHabitsDto->getDate(),
                $habitsId
            );
            return !empty($isResult);

        } catch (\Exception $exception) {
            $this->logger->error('Error in saveHabits', [
                'exception' => $exception->getMessage(),
                'user' => $userId ?? null,
                'habits_data' => $reqHabitsDto,
            ]);
            return false;
        }
    }



}