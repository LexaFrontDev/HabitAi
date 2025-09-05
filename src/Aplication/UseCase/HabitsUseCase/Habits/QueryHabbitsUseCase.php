<?php

namespace App\Aplication\UseCase\HabitsUseCase\Habits;

use App\Aplication\Mapper\FilterCriteriaMappers\Habits\SelectAllHabitsWithLimitMapper;
use App\Domain\Exception\Message\MessageException;
use App\Domain\Exception\NotFoundException\NotFoundException;
use App\Domain\Port\TokenProviderInterface;
use App\Domain\Repository\Habits\HabitsRepositoryInterface;
use App\Domain\Service\JwtServicesInterface;
use App\Domain\Service\QueriFilterInterface\FilterInterface;
use Doctrine\DBAL\Exception;
use Psr\Log\LoggerInterface;

class QueryHabbitsUseCase
{
    public function __construct(
        private TokenProviderInterface $tokenProvider,
        private HabitsRepositoryInterface $habitsRepository,
        private JwtServicesInterface $jwtServices,
        private LoggerInterface $logger,
        private SelectAllHabitsWithLimitMapper $selectAllHabitsWithLimit,
        private FilterInterface $filter,
    ) {
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getHabitsForDate(string $dateString): array
    {
        try {
            $token = $this->tokenProvider->getTokens();
            $userId = $this->jwtServices->getUserInfoFromToken($token->getAccessToken())->getUserId();
            $targetDate = new \DateTimeImmutable($dateString);
            $dayOfMonth = (int) $targetDate->format('d');
            $month = (int) $targetDate->format('m');
            $dayOfWeekNumber = (int) $targetDate->format('N');
            $results = $this->habitsRepository->getHabitsForToday($dayOfMonth, $dayOfWeekNumber, $month, $userId);
            if (empty($results)) {
                throw new NotFoundException('Привычки отсутствует');
            }
            $data = [];
            foreach ($results as $row) {
                $getTestMorgning = $this->determineTimePeriod($row['notification_date']);
                $row['period'] = $getTestMorgning;

                $row['date'] = [
                    'mon' => (bool) $row['mon'],
                    'tue' => (bool) $row['tue'],
                    'wed' => (bool) $row['wed'],
                    'thu' => (bool) $row['thu'],
                    'fri' => (bool) $row['fri'],
                    'sat' => (bool) $row['sat'],
                    'sun' => (bool) $row['sun'],
                ];

                $data[] = $row;
            }

            return $data;
        } catch (\Exception $e) {
            $this->logger->error('Error in getHabitsForToday: '.$e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
            ]);

            throw new MessageException('Ошибка сервере');
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     *
     * @throws Exception
     */
    public function getHabitsWidthLimit(int $limit, int $offset): array
    {
        $token = $this->tokenProvider->getTokens();
        $userId = $this->jwtServices->getUserInfoFromToken($token->getAccessToken())->getUserId();
        $dto = $this->selectAllHabitsWithLimit->toDto($userId, $limit, $offset);
        $obj = $this->filter->initFilter(criteriasDto: $dto, tableName: 'habits', alias: 'h', select: '*, h.id AS habit_id');
        $results = $obj->getList();

        if (empty($results)) {
            throw new NotFoundException('Привычки отсутствует', true);
        }


        $data = [];
        foreach ($results as $row) {
            $getTestMorgning = $this->determineTimePeriod($row['notification_date']);
            $row['period'] = $getTestMorgning;

            $row['date'] = [
                'mon' => (bool) $row['mon'],
                'tue' => (bool) $row['tue'],
                'wed' => (bool) $row['wed'],
                'thu' => (bool) $row['thu'],
                'fri' => (bool) $row['fri'],
                'sat' => (bool) $row['sat'],
                'sun' => (bool) $row['sun'],
            ];

            $data[] = $row;
        }

        return $data;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getHabitsForToday(): array
    {
        try {
            $token = $this->tokenProvider->getTokens();
            $userId = $this->jwtServices->getUserInfoFromToken($token->getAccessToken())->getUserId();

            $dayOfWeekNumber = (int) date('N');
            $dayOfMonth = (int) date('d');
            $month = (int) date('m');

            $results = $this->habitsRepository->getHabitsForToday($dayOfMonth, $dayOfWeekNumber, $month, $userId);

            if (empty($results)) {
                return [];
            }

            $data = [];
            foreach ($results as $row) {
                $getTestMorgning = $this->determineTimePeriod($row['notification_date']);
                $row['period'] = $getTestMorgning;

                $row['date'] = [
                    'mon' => (bool) $row['mon'],
                    'tue' => (bool) $row['tue'],
                    'wed' => (bool) $row['wed'],
                    'thu' => (bool) $row['thu'],
                    'fri' => (bool) $row['fri'],
                    'sat' => (bool) $row['sat'],
                    'sun' => (bool) $row['sun'],
                ];

                $data[] = $row;
            }

            return $data;
        } catch (\Exception $e) {
            $this->logger->error('Error in getHabitsForToday: '.$e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
            ]);

            throw new MessageException('Ошибка сервере');
        }
    }

    public function getCountHabitsToDay(): int
    {
        $token = $this->tokenProvider->getTokens();
        $userId = $this->jwtServices->getUserInfoFromToken($token->getAccessToken())->getUserId();

        $dayOfWeekNumber = (int) date('N');
        $dayOfMonth = (int) date('d');
        $month = (int) date('m');

        $result = $this->habitsRepository->getCountHabitsToday($dayOfMonth, $dayOfWeekNumber, $month, $userId);
        if (empty($result)) {
            return 0;
        }

        return $result;
    }

    /**
     * @return "morning"|"afternoon"|"evening"|"night"
     */
    public function determineTimePeriod(string $date): string
    {
        [$hour, $minutes] = explode(':', $date);
        $hour = (int) $hour;

        if ($hour >= 5 && $hour < 12) {
            return 'morning';
        }

        if ($hour >= 12 && $hour < 17) {
            return 'afternoon';
        }

        if ($hour >= 17 && $hour < 21) {
            return 'evening';
        }

        return 'night';
    }
}
