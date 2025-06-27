<?php

namespace App\Aplication\UseCase\PomodorUseCases\Commands;

use App\Aplication\Dto\PomodorDto\RepPomodorDto;
use App\Aplication\Dto\PomodorDto\ReqPomodorDto;
use App\Domain\Entity\Pomdoro\PomodorHistory;
use App\Domain\Repository\Pomodor\PomodorHistoryRepositoryInterface;

class PomodorCommandUseCase
{

    public function __construct(
        private PomodorHistoryRepositoryInterface $pomodorHistoryRepository,
    ){}

    public function calculatePeriodLabel(int $seconds): string
    {
        $minutes = (int) round($seconds / 60);

        if ($minutes >= 60) {
            $hours = intdiv($minutes, 60);
            $remainingMinutes = $minutes % 60;
            $label = $hours . 'H';
            if ($remainingMinutes > 0) {
                $label .= ' ' . $remainingMinutes . 'm';
            }
            return $label;
        }

        return $minutes . 'm';
    }



    public function savePomdor(ReqPomodorDto $reqPomodorDto): PomodorHistory
    {
        $periodLabel = $this->calculatePeriodLabel($reqPomodorDto->getTimeFocus());

        $data = new RepPomodorDto(
            $reqPomodorDto->getUserId(),
            $reqPomodorDto->getTimeFocus(),
            $reqPomodorDto->getTimeStart(),
            $reqPomodorDto->getTimeEnd(),
            $reqPomodorDto->getCreatedDate(),
            $periodLabel
        );

        return $this->pomodorHistoryRepository->cretePomodorHistory($data);
    }


}