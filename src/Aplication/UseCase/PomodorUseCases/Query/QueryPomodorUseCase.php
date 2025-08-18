<?php

namespace App\Aplication\UseCase\PomodorUseCases\Query;

use App\Aplication\Dto\PomodorDto\RepPomodoroHistory;
use App\Aplication\Dto\PomodorDto\ResPomdorCountStatistic;
use App\Domain\Entity\Pomdoro\PomodorHistory;
use App\Domain\Exception\Message\MessageException;
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
    ) {
    }

    private function initUserFromRequest(): void
    {
        $token = $this->tokenProvider->getTokens();
        $user = $this->jwtServices->getUserInfoFromToken($token->getAccessToken());
        $this->userId = $user->getUserId();
    }

    /**
     * @throws MessageException
     */
    public function getCountAndPeriodLabel(string $target): ResPomdorCountStatistic
    {
        $this->initUserFromRequest();
        $entries = $this->pomodorHistoryRepository
            ->getDataByUserIdAndPeriod($this->userId, $this->targetToDays($target));

        if (empty($entries)) {
            throw new MessageException("Нет данных для периода {$target}");
        }

        $summary = $this->aggregate($entries);

        return new ResPomdorCountStatistic(periodLabel:  $summary['periodLabel'], count: $summary['count'], target: $target);
    }

    /**
     * @return array{periodLabel: string, count: int}|bool
     */
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

    /**
     * @throws MessageException
     */
    public function getAllCountPomo(): int
    {
        $this->initUserFromRequest();
        $result = $this->pomodorHistoryRepository->getAllCountPomodorByUserId($this->userId);

        if (empty($result) || !is_int($result)) {
            throw new MessageException('Не удалось получить количество помодоро');
        }

        return $result;
    }

    /**
     * Агрегирует список записей, переводя значения времени в минуты и суммируя их.
     * Преобразует формат вида "2H" или "30m" в общий результат в часах и минутах.
     *
     * @param PomodorHistory[] $entries
     *
     * @return array{count: int, periodLabel: string}
     */
    private function aggregate(array $entries): array
    {
        $count = count($entries);
        $minutesTotal = 0;

        for ($i = 0; $i < $count; ++$i) {
            $label = $entries[$i]->getPeriodLabel();

            if (preg_match('/^(\d+)H$/i', $label, $matches)) {
                $minutesTotal += (int) $matches[1] * 60;
            } elseif (preg_match('/^(\d+)m$/i', $label, $matches)) {
                $minutesTotal += (int) $matches[1];
            }
        }

        $hours = intdiv($minutesTotal, 60);
        $minutes = $minutesTotal % 60;

        $periodLabel = trim(
            ($hours > 0 ? $hours.'H ' : '').
            ($minutes > 0 ? $minutes.'m' : '')
        ) ?: '0m';

        return ['count' => $count, 'periodLabel' => $periodLabel];
    }

    /**
     * @return RepPomodoroHistory[]
     *
     * @throws MessageException
     */
    public function getPomodorHistoryByUserId(int $limit = 50): array
    {
        $this->initUserFromRequest();
        $result = $this->pomodorHistoryRepository->getHistoryByUserId($this->userId, $limit);

        if (empty($result)) {
            throw new MessageException('Не удалось получить историю помодоро');
        }

        return $result;
    }

    private function targetToDays(string $target): int
    {
        return match ($target) {
            'week' => 7,
            'month' => 30,
            'year' => 365,
            default => throw new MessageException("Некорректный период: {$target}"),
        };
    }
}
