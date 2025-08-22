<?php

namespace App\Aplication\UseCase\PomodorUseCases\Commands;

use App\Aplication\Dto\PomodorDto\ReqPomodorDto;
use App\Aplication\Mapper\UseCaseMappers\PomodoroMapper\RepPomodoroDtoMapper;
use App\Domain\Entity\Pomdoro\PomodorHistory;
use App\Domain\Port\TokenProviderInterface;
use App\Domain\Repository\Pomodor\PomodorHistoryRepositoryInterface;
use App\Domain\Service\JwtServicesInterface;

class PomodorCommandUseCase
{
    public function __construct(
        private PomodorHistoryRepositoryInterface $pomodorHistoryRepository,
        private TokenProviderInterface $tokenProvider,
        private JwtServicesInterface $jwtServices,
        private RepPomodoroDtoMapper $repPomodoroDtoMapper,
    ) {
    }

    public function calculatePeriodLabel(int $seconds): string
    {
        $minutes = (int) round($seconds / 60);

        if ($minutes >= 60) {
            $hours = intdiv($minutes, 60);
            $remainingMinutes = $minutes % 60;
            $label = $hours.'H';
            if ($remainingMinutes > 0) {
                $label .= ' '.$remainingMinutes.'m';
            }

            return $label;
        }

        return $minutes.'m';
    }

    public function savePomdor(ReqPomodorDto $reqPomodorDto): PomodorHistory
    {
        $periodLabel = $this->calculatePeriodLabel($reqPomodorDto->getTimeFocus());
        $token = $this->tokenProvider->getTokens();
        $userId = $this->jwtServices->getUserInfoFromToken($token->getAccessToken())->getUserId();
        $dto = $this->repPomodoroDtoMapper->ReqPomodorToRepPomodorDto(userId: $userId, reqPomodorDto: $reqPomodorDto, periodLabel: $periodLabel);

        return $this->pomodorHistoryRepository->cretePomodorHistory($dto);
    }
}
