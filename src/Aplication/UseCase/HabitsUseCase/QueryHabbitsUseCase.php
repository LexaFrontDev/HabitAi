<?php

namespace App\Aplication\UseCase\HabitsUseCase;

use App\Domain\Entity\Habits\Habit;
use App\Domain\Entity\Purpose\Purpose;
use App\Domain\Port\TokenProviderInterface;
use App\Domain\Repository\Habits\HabitsRepositoryInterface;
use App\Domain\Service\JwtServicesInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;

class QueryHabbitsUseCase
{



    public function __construct(
        private TokenProviderInterface $tokenProvider,
        private HabitsRepositoryInterface $habitsRepository,
        private JwtServicesInterface $jwtServices,
        private LoggerInterface $logger
    ){}




    public function getHabitsForDate($dateString): array
    {
        $token = $this->tokenProvider->getTokens();
        $userId = $this->jwtServices->getUserInfoFromToken($token->getAccessToken())->getUserId();
        $targetDate = new \DateTimeImmutable($dateString);
        $dayOfWeekNumber = $targetDate->format('N');
        $dayOfMonth = $targetDate->format('d');
        $month = $targetDate->format('m');
        $results = $this->habitsRepository->getHabitsForToday($dayOfMonth, $dayOfWeekNumber, $month, $userId);
        if (empty($results)) {
            return [];
        }
        $data = [];
        foreach ($results as $row) {
            $getTestMorgning  =  $this->determineTimePeriod($row['notification_date']);
            $row['period'] = $getTestMorgning;
            $data[] = $row;
        }
        return $data;
    }



    public function getHabitsForToday(): array
    {
        try {
            $token = $this->tokenProvider->getTokens();
            $userId = $this->jwtServices->getUserInfoFromToken($token->getAccessToken())->getUserId();

            $dayOfWeekNumber = date('N');
            $dayOfMonth = date('d');
            $month = date('m');

            $results = $this->habitsRepository->getHabitsForToday($dayOfMonth, $dayOfWeekNumber, $month, $userId);

            if (empty($results)) {
                return [];
            }

            $data = [];
            foreach ($results as $row) {
                $getTestMorgning = $this->determineTimePeriod($row['notification_date']);
                $row['period'] = $getTestMorgning;

                $row['date'] = [
                    'mon' => (bool)$row['mon'],
                    'tue' => (bool)$row['tue'],
                    'wed' => (bool)$row['wed'],
                    'thu' => (bool)$row['thu'],
                    'fri' => (bool)$row['fri'],
                    'sat' => (bool)$row['sat'],
                    'sun' => (bool)$row['sun'],
                ];

                $data[] = $row;
            }

            return $data;
        } catch (\Exception $e) {
            $this->logger->error('Error in getHabitsForToday: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
            ]);

            return [];
        }
    }





    public function getCountHabitsToDay(): int
    {
        $token = $this->tokenProvider->getTokens();
        $userId = $this->jwtServices->getUserInfoFromToken($token->getAccessToken())->getUserId();

        $dayOfWeekNumber = date('N');
        $dayOfMonth = date('d');
        $month = date('m');

        $result = $this->habitsRepository->getCountHabitsToday($dayOfMonth, $dayOfWeekNumber, $month, $userId);
        if (empty($result)) {
            return 0;
        }

        return $result;
    }



    /**
     * @return "morning"|"afternoon"|"evening"|"night"|false
     * @param "08:30"
     */
    public function determineTimePeriod(string $date): string|bool
    {
        [$hour, $minutes] = explode(':', $date);
        $hour = (int)$hour;

        if ($hour >= 5 && $hour < 12) {
            return 'morning';
        } elseif ($hour >= 12 && $hour < 17) {
            return 'afternoon';
        } elseif ($hour >= 17 && $hour < 21) {
            return 'evening';
        } elseif ($hour >= 21 || $hour < 5) {
            return 'night';
        }

        return false;
    }
}