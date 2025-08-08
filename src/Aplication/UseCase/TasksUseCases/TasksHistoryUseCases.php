<?php

namespace App\Aplication\UseCase\TasksUseCases;

use App\Domain\Exception\Message\MessageException;
use App\Domain\Repository\Tasks\TasksHistoryInterface;
use App\Domain\Service\JwtServicesInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;

class TasksHistoryUseCases
{

   public function __construct(
       private JwtServicesInterface $jwtServices,
       private TasksHistoryInterface $tasksHistoryInterface,
       private LoggerInterface $logger
   ){}


    /**
     * @param Request $request
     * @param int $tasksId
     * @param string|null $monthly
     * @param string|null $date
     * @return int
     */
    public function saveToDo(Request $request, int $tasksId, string $monthly = null, string $date = null): int
    {
        $token = $this->jwtServices->getTokens($request);
        $user = $this->jwtServices->getUserInfoFromToken($token['accessToken']);
        $userId = $user->getUserId();
        $result = $this->tasksHistoryInterface->tasksToDoSave($userId, $tasksId, $monthly, $date);

        if(empty($result)){
            throw new MessageException('Не удалось сохранить выполнение задачи');
        }

        return $result;
    }


    /**
     * @param Request $request
     * @param int $tasksId
     * @return bool
     */
    public function deleteToDo(Request $request, int $tasksId): bool
    {
        $token = $this->jwtServices->getTokens($request);
        $user = $this->jwtServices->getUserInfoFromToken($token['accessToken']);
        $userId = $user->getUserId();
        $isDelete= $this->tasksHistoryInterface->tasksWontDo($tasksId, $userId);
        if(empty($isDelete)){
            throw new MessageException('Не удалось удалить выполнение задачи');
        }

        return true;
    }








}