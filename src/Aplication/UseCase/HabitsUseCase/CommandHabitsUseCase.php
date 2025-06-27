<?php

namespace App\Aplication\UseCase\HabitsUseCase;

use App\Aplication\Dto\HabitsDtoUseCase\ReqHabitsDto;
use App\Aplication\Dto\HabitsDtoUseCase\SaveHabitDto;
use App\Aplication\UseCase\DatesUseCases\DatesCommandUseCase;
use App\Domain\Repository\DatesRepository\DatesDailyRepositoryInterface;
use App\Domain\Repository\DatesWeekly\DatesWeeklyRepositoryInterface;
use App\Domain\Repository\DayesRepeatRepository\DatesRepeatRepositoryInterface;
use App\Domain\Repository\Habits\HabitsRepositoryInterface;
use App\Domain\Repository\PurposeRepository\PurposeRepositoryInterface;
use App\Domain\Service\JwtServicesInterface;
use App\Domain\Service\Tokens\AuthTokenServiceInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Aplication\Dto\PurposeDto\PurposeDto;

class CommandHabitsUseCase
{

    public function __construct(
        private HabitsRepositoryInterface $habitsRepository,
        private DatesCommandUseCase $datesCommandUseCase,
        private PurposeRepositoryInterface $purposeRepository,
        private AuthTokenServiceInterface $authTokenService,
        private JwtServicesInterface $jwtServices,
        private LoggerInterface $logger,
    ){}





    public function saveHabits(ReqHabitsDto $reqHabitsDto, Request $request): bool
    {
        try {
            $token = $this->authTokenService->getTokens($request);
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