<?php

namespace App\Aplication\UseCase\TasksUseCases;

use App\Aplication\Dto\TasksDto\TasksDay;
use App\Domain\Exception\NotFoundException\NotFoundException;
use App\Domain\Repository\Tasks\TasksInterface;
use App\Domain\Service\JwtServicesInterface;
use phpDocumentor\Reflection\DocBlock\Tags\Throws;
use phpDocumentor\Reflection\Types\False_;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;

class QueryTasksUseCase
{

    public function __construct(
        private JwtServicesInterface $jwtServices,
        private TasksInterface $tasksRepository,
        private LoggerInterface $logger,

    ){}


    /**
     * @param int $day
     * @param Request $request
     * @return false Массив задач за день
     */
    public function getTasksByDay(int $day, Request $request): array|bool
    {
        $token = $this->jwtServices->getTokens($request);
        $user = $this->jwtServices->getUserInfoFromToken($token['accessToken']);
        $userId = $user->getUserId();

        try{
            $result = $this->tasksRepository->getTasksByDay($userId, $day);
            if(!empty($result)){
                return $result;
            }

            return false;
        }catch (\Exception $exception){
            $this->logger->error($exception->getMessage(), $exception->getTrace(), ['className' => get_class($this)]);
            return false;
        }
    }


    /**
     * @param Request $request
     * @return TasksDay[]
    */
    public function getTasksAll( Request $request): array
    {
        $token = $this->jwtServices->getTokens($request);
        $user = $this->jwtServices->getUserInfoFromToken($token['accessToken']);
        $userId = $user->getUserId();

        $isResult = $this->tasksRepository->getTasksAllByUserId($userId);

        if(!empty($isResult)){
            return $isResult;
        }

        return  throw new NotFoundException('Задачи не создано');
    }

}