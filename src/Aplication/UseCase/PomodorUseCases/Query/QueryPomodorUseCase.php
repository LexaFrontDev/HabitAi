<?php
namespace App\Aplication\UseCase\PomodorUseCases\Query;

use App\Aplication\Dto\PomodorDto\RepPomodoroHistory;
use App\Aplication\Dto\PomodorDto\ResPomdorCountStatistic;
use App\Domain\Port\TokenProviderInterface;
use App\Domain\Repository\Pomodor\PomodorHistoryRepositoryInterface;
use App\Domain\Service\JwtServicesInterface;


class QueryPomodorUseCase
{
    private int $userId;

    public function __construct(
        private TokenProviderInterface $tokenProvider,
        private JwtServicesInterface $jwtServices,
        private PomodorHistoryRepositoryInterface $pomodorHistoryRepository,
    ){}

    private function initUserFromRequest(): void
    {
        $token = $this->tokenProvider->getTokens();
        $user = $this->jwtServices->getUserInfoFromToken($token->getAccessToken());
        $this->userId = $user->getUserId();
    }

    public function getCountAndPeriodLabel(string $target): ResPomdorCountStatistic|bool
    {
        try {
            $this->initUserFromRequest();
            $entries = $this->pomodorHistoryRepository->getDataByUserIdAndPeriod($this->userId, $this->targetToDays($target));
            $summary = $this->aggregate($entries);
            return new ResPomdorCountStatistic($target, $summary['periodLabel'], $summary['count']);
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getCountAndPeriodLabelToday(): array|bool
    {
        try {
            $this->initUserFromRequest();
            $entries = $this->pomodorHistoryRepository->getPomoDayByUserId($this->userId);
            $summary = $this->aggregate($entries);
            return ['periodLabel' => $summary['periodLabel'], 'count' => $summary['count']];
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getAllCountPomo(): int|bool
    {
        $this->initUserFromRequest();
        return $this->pomodorHistoryRepository->getAllCountPomodorByUserId($this->userId) ?? false;
    }

    private function aggregate(array $entries): array
    {
        $count = count($entries);
        $minutesTotal = 0;
        foreach ($entries as $entry) {
            $label = $entry->getPeriodLabel();
            if (str_ends_with($label, 'H')) {
                $minutesTotal += ((int) $label) * 60;
            } elseif (str_ends_with($label, 'm')) {
                $minutesTotal += (int) $label;
            }
        }
        $hours = floor($minutesTotal / 60);
        $minutes = $minutesTotal % 60;
        $periodLabel = trim(($hours > 0 ? $hours . 'H ' : '') . ($minutes > 0 ? $minutes . 'm' : '')) ?: '0m';
        return ['count' => $count, 'periodLabel' => $periodLabel];
    }


    public function getPomodorHistoryByUserId(int $limit = 50): array|bool
    {
        $this->initUserFromRequest();
        return $this->pomodorHistoryRepository->getHistoryByUserId($this->userId, $limit)?? false;
    }


    private function targetToDays(string $target): int
    {
        return match ($target) {
            'week' => 7,
            'month' => 30,
            'year' => 365,
            default => 7,
        };
    }
}
