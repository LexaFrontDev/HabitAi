<?php

namespace App\Aplication\UseCase\HabitsUseCase;

use App\Aplication\Dto\HabitsDtoUseCase\RepSaveHabitsProgressDto;
use App\Aplication\Dto\HabitsDtoUseCase\SaveHabitsProgress;
use App\Domain\Repository\Habits\HabitsHistoryRepositoryInterface;
use App\Domain\Repository\PurposeRepository\PurposeRepositoryInterface;
use App\Domain\Service\JwtServicesInterface;
use App\Domain\Service\Tokens\AuthTokenServiceInterface;
use Symfony\Component\HttpFoundation\Request;

class CommandHabitsHistoryUseCase
{


    public function __construct(
        private AuthTokenServiceInterface $authTokenService,
        private JwtServicesInterface $jwtServices,
        private HabitsHistoryRepositoryInterface $habitsHistoryRepository,
        private PurposeRepositoryInterface $purposeRepository,
    ){}



    public function saveHabitsProgress(SaveHabitsProgress $saveHabitsProgress, Request $request): array|bool
    {
        if ($saveHabitsProgress->getUserId() === null) {
            $token = $this->authTokenService->getTokens($request);
            $userId = $this->jwtServices->getUserInfoFromToken($token['accessToken'])->getUserId();
            $saveHabitsProgress->userId = $userId;
        }

        $countProgresses = $this->purposeRepository->getPurposeCountByHabitId($saveHabitsProgress->getHabitsId());

        $isSave = $this->habitsHistoryRepository->saveProgress($saveHabitsProgress, $countProgresses);
        if(!empty($isSave)) return ['success' => true, 'data' => $isSave];
        return ['success' => false];
    }


}