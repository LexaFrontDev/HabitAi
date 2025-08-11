<?php

namespace App\Aplication\UseCase\HabitsUseCase;

use App\Aplication\Dto\HabitsDtoUseCase\RepSaveHabitsProgressDto;
use App\Aplication\Dto\HabitsDtoUseCase\SaveHabitsProgress;
use App\Domain\Port\TokenProviderInterface;
use App\Domain\Repository\Habits\HabitsHistoryRepositoryInterface;
use App\Domain\Repository\Purpose\PurposeRepositoryInterface;
use App\Domain\Service\JwtServicesInterface;
use Symfony\Component\HttpFoundation\Request;

class CommandHabitsHistoryUseCase
{


    public function __construct(
        private TokenProviderInterface $tokenProvider,
        private JwtServicesInterface $jwtServices,
        private HabitsHistoryRepositoryInterface $habitsHistoryRepository,
        private PurposeRepositoryInterface $purposeRepository,
    ){}



    public function saveHabitsProgress(SaveHabitsProgress $saveHabitsProgress): array|bool
    {
        if ($saveHabitsProgress->getUserId() === null) {
            $token = $this->tokenProvider->getTokens();
            $userId = $this->jwtServices->getUserInfoFromToken($token->getAccessToken())->getUserId();
            $saveHabitsProgress->userId = $userId;
        }

        $countProgresses = $this->purposeRepository->getPurposeCountByHabitId($saveHabitsProgress->getHabitsId());

        $isSave = $this->habitsHistoryRepository->saveProgress($saveHabitsProgress, $countProgresses);
        if(!empty($isSave)) return ['success' => true, 'data' => $isSave];
        return ['success' => false];
    }


}