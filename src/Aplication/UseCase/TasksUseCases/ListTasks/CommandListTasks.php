<?php

namespace App\Aplication\UseCase\TasksUseCases\ListTasks;

use App\Aplication\Dto\TasksDto\ListTasks\TasksListReq;
use App\Aplication\Dto\TasksDto\ListTasks\TasksListReqWithListId;
use App\Aplication\Mapper\UseCaseMappers\Tasks\TaskLists\CreateListMapper;
use App\Aplication\Mapper\UseCaseMappers\Tasks\TaskLists\UpdateListMapper;
use App\Domain\Exception\Message\MessageException;
use App\Domain\Port\TokenProviderInterface;
use App\Domain\Repository\Tasks\ListTasksInterface;
use App\Domain\Service\JwtServicesInterface;

class CommandListTasks
{
    public function __construct(
        private TokenProviderInterface $tokenProvider,
        private JwtServicesInterface $jwtServices,
        private CreateListMapper $createListMapper,
        private ListTasksInterface $listTasks,
        private UpdateListMapper $updateListMapper,
    ) {
    }

    public function saveListTasks(TasksListReq $tasksListReq): bool
    {
        $token = $this->tokenProvider->getTokens();
        $userId = $this->jwtServices
            ->getUserInfoFromToken($token->getAccessToken())
            ->getUserId();

        $dto = $this->createListMapper->isReqToDto($userId, $tasksListReq);
        $isResult = $this->listTasks->saveListTask($dto);
        if (empty($isResult)) {
            throw new MessageException('Не удалось создать список задач');
        }

        return true;
    }

    public function updateListTasks(TasksListReqWithListId $tasksListReq): bool
    {
        $token = $this->tokenProvider->getTokens();
        $userId = $this->jwtServices
            ->getUserInfoFromToken($token->getAccessToken())
            ->getUserId();

        $dto = $this->updateListMapper->isReqToDto($userId, $tasksListReq);
        $isResult = $this->listTasks->updateListTask($dto);
        if (empty($isResult)) {
            throw new MessageException('Не удалось обновить список задач');
        }

        return true;
    }
}
