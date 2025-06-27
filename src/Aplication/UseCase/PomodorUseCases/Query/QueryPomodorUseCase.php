<?php
namespace App\Aplication\UseCase\PomodorUseCases\Query;

use App\Aplication\Dto\PomodorDto\RepPomodoroHistory;
use App\Aplication\Dto\PomodorDto\ResPomdorCountStatistic;
use App\Domain\Repository\Pomodor\PomodorHistoryRepositoryInterface;
use App\Domain\Service\JwtServicesInterface;
use App\Domain\Service\Tokens\AuthTokenServiceInterface;
use Symfony\Component\HttpFoundation\Request;

class QueryPomodorUseCase
{
    private int $userId;

    public function __construct(
        private AuthTokenServiceInterface $authTokenService,
        private JwtServicesInterface $jwtServices,
        private PomodorHistoryRepositoryInterface $pomodorHistoryRepository,
    ){}

    private function initUserFromRequest(Request $request): void
    {
        $token = $this->authTokenService->getTokens($request);
        $user = $this->jwtServices->getUserInfoFromToken($token['accessToken']);
        $this->userId = $user->getUserId();
    }

    public function getCountAndPeriodLabel(string $target, Request $request): ResPomdorCountStatistic|bool
    {
        try {
            $this->initUserFromRequest($request);
            $entries = $this->pomodorHistoryRepository->getDataByUserIdAndPeriod($this->userId, $this->targetToDays($target));
            $summary = $this->aggregate($entries);
            return new ResPomdorCountStatistic($target, $summary['periodLabel'], $summary['count']);
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getCountAndPeriodLabelToday(Request $request): array|bool
    {
        try {
            $this->initUserFromRequest($request);
            $entries = $this->pomodorHistoryRepository->getPomoDayByUserId($this->userId);
            $summary = $this->aggregate($entries);
            return ['periodLabel' => $summary['periodLabel'], 'count' => $summary['count']];
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getAllCountPomo(Request $request): int|bool
    {
        $this->initUserFromRequest($request);
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


    public function getPomodorHistoryByUserId(Request $request, int $limit = 50): array|bool
    {
        $this->initUserFromRequest($request);
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
