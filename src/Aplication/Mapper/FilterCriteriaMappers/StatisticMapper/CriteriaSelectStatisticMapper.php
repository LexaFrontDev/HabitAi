<?php

namespace App\Aplication\Mapper\FilterCriteriaMappers\StatisticMapper;

use App\Aplication\CriteriaFilters\EntityFindById;
use App\Aplication\CriteriaFilters\EntityFindByString;
use App\Aplication\CriteriaFilters\PaginationDto;
use App\Aplication\Dto\FiltersWithCriterias\Habits\FilterSelectStatistics;

class CriteriaSelectStatisticMapper
{
    /**
     * @param string[] $year
     */
    public function mapToDto(int $userId, string $name, array $year, int $limit = 5, int $offset = 0): ?FilterSelectStatistics
    {
        return new FilterSelectStatistics(
            user_id: new EntityFindById(id: $userId),
            year: new EntityFindByString(in: $year),
            stat_type: new EntityFindByString(equal:  $name),
            pagination: new PaginationDto(limit: $limit, offset: $offset),
        );
    }

    /**
     * @return array{
     *      user_id: int|null,
     *      year: string[],
     *      stat_type: string|null,
     *      limit: int,
     *     offset: int
     * }
     */
    public function toArray(FilterSelectStatistics $dto): array
    {
        return [
            'user_id' => $dto->user_id->id ?? null,
            'year' => $dto->year->in ?? [],
            'stat_type' => $dto->stat_type->equal ?? null,
            'limit' => $dto->pagination->limit ?? 5,
            'offset' => $dto->pagination->offset ?? 0,
        ];
    }
}
