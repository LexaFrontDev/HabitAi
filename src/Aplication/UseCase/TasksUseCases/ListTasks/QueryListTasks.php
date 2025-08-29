<?php

namespace App\Aplication\UseCase\TasksUseCases\ListTasks;

use App\Aplication\CriteriaFilters\EntityFindById;
use App\Aplication\CriteriaFilters\PaginationDto;
use App\Aplication\Dto\TasksDto\ListTasks\TasksListRes;
use App\Domain\Exception\NotFoundException\NotFoundException;
use App\Domain\Port\TokenProviderInterface;
use App\Domain\Service\JwtServicesInterface;
use App\Domain\Service\QueriFilterInterface\FilterInterface;

class QueryListTasks
{
    public function __construct(
        private TokenProviderInterface $tokenProvider,
        private JwtServicesInterface $jwtServices,
        private FilterInterface $filter,
    ) {
    }

    /**
     * @return TasksListRes[]
     */
    public function getAllList(int $limit = 50, int $offset = 0): array
    {
        $token = $this->tokenProvider->getTokens();
        $userId = $this->jwtServices
            ->getUserInfoFromToken($token->getAccessToken())
            ->getUserId();

        $dto = (object) [
            'user_id'   => new EntityFindById(id: $userId),
            'pagination' => new PaginationDto(limit: $limit, offset: $offset),
        ];

        $obj = $this->filter->initFilter(
            criteriasDto: $dto,
            tableName: 'list_tasks',
            alias: 'lt',
            select: '*'
        );

        $rows = $obj->getList();

        if (empty($rows)) {
            throw new NotFoundException('Нету списка');
        }

        $result = [];

        foreach ($rows as $row) {
            $result[] = new TasksListRes(
                label: $row['label'] ?? '',
                priority: $row['priority'] ?? '',
                list_type: $row['list_type'] ?? ''
            );
        }

        return $result;
    }
}
