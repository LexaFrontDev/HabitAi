<?php

namespace App\Aplication\UseCase\HabitsUseCase;

use App\Domain\Repository\Habits\HabitsHistoryRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;

class CommandHabitsHistoryUseCase
{


    public function __construct(
        private HabitsHistoryRepositoryInterface $habitsHistoryRepository
    ){}

    public function saveHabitsProgress(Request $request)
    {





    }


}