<?php

namespace App\Aplication\UseCase\Statistics;

use App\Aplication\Mapper\FilterCriteriaMappers\StatisticMapper\CriteriaSelectStatisticMapper;
use App\Domain\Port\TokenProviderInterface;
use App\Domain\Service\JwtServicesInterface;
use App\Domain\Service\QueriFilterInterface\FilterInterface;
use Doctrine\DBAL\Exception;

class StatisticsUseCase
{
    public function __construct(
        private FilterInterface $filter,
        private CriteriaSelectStatisticMapper $criteriaSelectStatisticMapper,
        private TokenProviderInterface $tokenProvider,
        private JwtServicesInterface $jwtServices,
    ) {
    }

    /**
     * @param string[] $year
     *
     * @return mixed[]
     *
     * @throws Exception
     */
    public function getStaticInfoByYears(array $year, string $labelStatic, int $limit = 50, int $offset = 0): array
    {
        $token = $this->tokenProvider->getTokens();
        $userId = $this->jwtServices->getUserInfoFromToken($token->getAccessToken())->getUserId();
        $mapper = $this->criteriaSelectStatisticMapper->mapToDto(userId:  $userId, name: $labelStatic, year: $year, limit: $limit, offset: $offset);
        $result = $this->filter->initFilter(criteriasDto:  $mapper, tableName: 'statistic', alias: 's');

        return $result->getList();
    }
}
