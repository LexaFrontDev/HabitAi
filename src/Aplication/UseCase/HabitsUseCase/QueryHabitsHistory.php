<?php

namespace App\Aplication\UseCase\HabitsUseCase;

use App\Aplication\Dto\HabitsDto\GetHabitsProgress;
use App\Aplication\Dto\HabitsDto\GetHabitsProgressHabitsTitle;
use App\Aplication\Dto\HabitsDto\StaticHabitsAllDto;
use App\Aplication\Mapper\FilterCriteriaMappers\Habits\CriteriaSelectHabitsStatisticAll;
use App\Aplication\Mapper\FilterCriteriaMappers\StatisticMapper\CriteriaSelectStatisticAllMapper;
use App\Domain\Exception\Message\MessageException;
use App\Domain\Exception\NotFoundException\NotFoundException;
use App\Domain\Port\TokenProviderInterface;
use App\Domain\Repository\Habits\HabitsHistoryRepositoryInterface;
use App\Domain\Service\JwtServicesInterface;
use App\Domain\Service\QueriFilterInterface\FilterInterface;

class QueryHabitsHistory
{
    public function __construct(
        private TokenProviderInterface $tokenProvider,
        private HabitsHistoryRepositoryInterface $habitsHistoryRepository,
        private JwtServicesInterface $jwtServices,
        private CriteriaSelectStatisticAllMapper $criteriaSelectStatisticAllMapper,
        private FilterInterface $filter,
        private CriteriaSelectHabitsStatisticAll $criteriaSelectHabitsStatisticAll,
    ) {
    }

    public function getDoneHabitsCount(): int
    {
        $token = $this->tokenProvider->getTokens();
        $userId = $this->jwtServices->getUserInfoFromToken($token->getAccessToken())->getUserId();
        $result = $this->habitsHistoryRepository->getCountDoneHabits($userId);
        if (empty($result) || !is_int($result)) {
            throw new MessageException('Не удалось получить историю привычек');
        }

        return $result;
    }

    /**
     * @return GetHabitsProgress[]|false
     * */
    public function getAllProgressHabits(): array|false
    {
        $token = $this->tokenProvider->getTokens();
        $userId = $this->jwtServices->getUserInfoFromToken($token->getAccessToken())->getUserId();

        return $this->habitsHistoryRepository->getAllProgress($userId);
    }

    /**
     * @return GetHabitsProgressHabitsTitle[]|false
     * */
    public function getAllProgressWithHabitsTitle(): array|false
    {
        $token = $this->tokenProvider->getTokens();
        $userId = $this->jwtServices->getUserInfoFromToken($token->getAccessToken())->getUserId();

        return $this->habitsHistoryRepository->getAllProgressWithHabitsTitle($userId);
    }

    /**
     * @throws \DateMalformedStringException
     */
    public function getAllProgressWithHabitsTitleByHabitsId(int $habitsId): StaticHabitsAllDto|false
    {
        $token = $this->tokenProvider->getTokens();
        $userId = $this->jwtServices->getUserInfoFromToken($token->getAccessToken())->getUserId();
        $dto = $this->criteriaSelectStatisticAllMapper->mapToDto(userId: $userId, habitId: $habitsId);
        $obj = $this->filter->initFilter(criteriasDto: $dto, tableName: 'habits_history', alias: 'hh', select: '*');
        $results  = $obj->getList();

        if (empty($results)) {
            throw new NotFoundException('История пустая');
        }

        $habitsList  = array_map(fn (array $row) => new GetHabitsProgressHabitsTitle(
            (int) $row['id'],
            $row['title'],
            (int) $row['habits_id'],
            (int) $row['count'],
            (int) $row['count_end'],
            (bool) $row['is_done'],
            new \DateTimeImmutable($row['recorded_at'])
        ), $results);

        $allTrackingDays = count($habitsList);
        $allTrackingCount = array_sum(array_map(fn (GetHabitsProgressHabitsTitle $h) => $h->countEnd, $habitsList));

        $today = new \DateTimeImmutable('today');
        $weekAgo = (new \DateTimeImmutable('today'))->modify('-6 days');

        $trackingToday = 0;
        $trackingWeek = 0;

        foreach ($habitsList as $h) {
            $recordedAt = $h->recordedAt;
            if ($recordedAt >= $today) {
                $trackingToday += $h->countEnd;
            }
            if ($recordedAt >= $weekAgo) {
                $trackingWeek += $h->countEnd;
            }
        }

        return new StaticHabitsAllDto(
            habitsList: $habitsList,
            all_tracking_days: $allTrackingDays,
            all_tracking_count: $allTrackingCount,
            tracking_today: $trackingToday,
            tracking_week: $trackingWeek
        );
    }

    /**
     * @return StaticHabitsAllDto[]|false
     *
     * @throws \DateMalformedStringException
     */
    /**
     * @return StaticHabitsAllDto[]|false
     *
     * @throws \DateMalformedStringException
     */
    public function getAllProgressWithHabitsTitleAll(): array|false
    {
        $token = $this->tokenProvider->getTokens();
        $userId = $this->jwtServices->getUserInfoFromToken($token->getAccessToken())->getUserId();
        $dto = $this->criteriaSelectHabitsStatisticAll->mapToDto(userId: $userId);

        $obj = $this->filter->initFilter(criteriasDto: $dto, tableName: 'habits_history', alias: 'hh', select: '*');

        $results = $obj->getList();

        if (empty($results)) {
            throw new NotFoundException('История пустая');
        }

        $habitsList = array_map(fn (array $row) => new GetHabitsProgressHabitsTitle(
            id: (int) $row['id'],
            title: $row['title'],
            habitId: (int) $row['habits_id'],
            count: (int) $row['count'],
            countEnd: (int) $row['count_end'],
            isDone: (bool) $row['is_done'],
            recordedAt: new \DateTimeImmutable($row['recorded_at'])
        ), $results);

        $today = new \DateTimeImmutable('today');
        $weekAgo = (new \DateTimeImmutable('today'))->modify('-6 days');


        $grouped = [];
        foreach ($habitsList as $h) {
            $hid = $h->habitId;
            if (!isset($grouped[$hid])) {
                $grouped[$hid] = [
                    'habitsList'       => [],
                    'all_tracking_days' => 0,
                    'all_tracking_count' => 0,
                    'tracking_today'   => 0,
                    'tracking_week'    => 0,
                ];
            }

            $grouped[$hid]['habitsList'][] = $h;
            ++$grouped[$hid]['all_tracking_days'];
            $grouped[$hid]['all_tracking_count'] += $h->countEnd;

            if ($h->recordedAt >= $today) {
                $grouped[$hid]['tracking_today'] += $h->countEnd;
            }
            if ($h->recordedAt >= $weekAgo) {
                $grouped[$hid]['tracking_week'] += $h->countEnd;
            }
        }


        $resultDtos = [];
        foreach ($grouped as $hid => $data) {
            $resultDtos[$hid] = new StaticHabitsAllDto(
                habitsList: $data['habitsList'],
                all_tracking_days: $data['all_tracking_days'],
                all_tracking_count: $data['all_tracking_count'],
                tracking_today: $data['tracking_today'],
                tracking_week: $data['tracking_week']
            );
        }

        return $resultDtos;
    }
}
