<?php

namespace App\Aplication\UseCase\HabitsUseCase\HabitsHistory;

use App\Aplication\Dto\HabitsDto\SaveHabitsProgress;
use App\Domain\Port\TokenProviderInterface;
use App\Domain\Repository\Habits\HabitsHistoryRepositoryInterface;
use App\Domain\Repository\Purpose\PurposeRepositoryInterface;
use App\Domain\Service\JwtServicesInterface;

class CommandHabitsHistoryUseCase
{
    public function __construct(
        private TokenProviderInterface $tokenProvider,
        private JwtServicesInterface $jwtServices,
        private HabitsHistoryRepositoryInterface $habitsHistoryRepository,
        private PurposeRepositoryInterface $purposeRepository,
    ) {
    }

    /**
     * @return array{success: bool, data?: mixed}|bool
     */
    public function saveHabitsProgress(SaveHabitsProgress $saveHabitsProgress): array|bool
    {
        if (null === $saveHabitsProgress->getUserId()) {
            $token = $this->tokenProvider->getTokens();
            $userId = $this->jwtServices->getUserInfoFromToken($token->getAccessToken())->getUserId();
            $saveHabitsProgress->userId = $userId;
        }

        $countProgresses = $this->purposeRepository->getPurposeCountByHabitId($saveHabitsProgress->getHabitsId());

        $isSave = $this->habitsHistoryRepository->saveProgress($saveHabitsProgress, $countProgresses);
        if (!empty($isSave)) {
            return ['success' => true, 'data' => $isSave];
        }

        return ['success' => false];
    }
}
