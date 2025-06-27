<?php

namespace App\Aplication\UseCase\HabitsUseCase;

use App\Domain\Entity\Habits\Habit;
use App\Domain\Entity\Purpose\Purpose;
use App\Domain\Repository\Habits\HabitsRepositoryInterface;
use App\Domain\Service\JwtServicesInterface;
use App\Domain\Service\Tokens\AuthTokenServiceInterface;
use Symfony\Component\HttpFoundation\Request;

class QueryHabbitsUseCase
{



    public function __construct(
        private HabitsRepositoryInterface $habitsRepository,
        private AuthTokenServiceInterface $authTokenService,
        private JwtServicesInterface $jwtServices,
    ){}




    public function getHabitsForDate(Request $request): array
    {
        $token = $this->authTokenService->getTokens($request);
        $userId = $this->jwtServices->getUserInfoFromToken($token['accessToken'])->getUserId();
        $dateString = $request->query->get('date');
        if (!$dateString) {
            return [];
        }
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



    public function getHabitsForToday(Request $request): array
    {
        $token = $this->authTokenService->getTokens($request);
        $userId = $this->jwtServices->getUserInfoFromToken($token['accessToken'])->getUserId();

        $dayOfWeekNumber = date('N');
        $dayOfMonth = date('d');
        $month = date('m');

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