<?php

namespace App\Aplication\Mapper\UseCaseMappers\PomodoroMapper;

use App\Aplication\Dto\PomodorDto\RepPomodorDto;
use App\Aplication\Dto\PomodorDto\ReqPomodorDto;

class RepPomodoroDtoMapper
{
    public function ReqPomodorToRepPomodorDto(int $userId, ReqPomodorDto $reqPomodorDto, string $periodLabel): RepPomodorDto
    {
        return  new RepPomodorDto(
            $reqPomodorDto->title,
            $userId,
            $reqPomodorDto->getTimeFocus(),
            $reqPomodorDto->getTimeStart(),
            $reqPomodorDto->getTimeEnd(),
            $reqPomodorDto->getCreatedDate(),
            $periodLabel
        );
    }
}
