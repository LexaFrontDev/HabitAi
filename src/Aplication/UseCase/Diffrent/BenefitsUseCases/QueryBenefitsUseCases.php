<?php

namespace App\Aplication\UseCase\Diffrent\BenefitsUseCases;

use App\Aplication\Dto\BenefistDto\BenefitsDtoRes;
use App\Domain\Exception\NotFoundException\NotFoundException;
use App\Domain\Service\QueriFilterInterface\FilterInterface;

class QueryBenefitsUseCases
{
    public function __construct(
        private FilterInterface $filter,
    ) {
    }

    /**
     * @return BenefitsDtoRes[]
     */
    public function getAllBenefits(): array
    {
        $obj = $this->filter->initFilter(
            criteriasDto: null,
            tableName: 'benefits',
            alias: 'b',
            select: '*'
        );
        $result = $obj->getList();

        if (empty($result)) {
            throw new NotFoundException('Не удалось получить список планов');
        }

        $benefits = [];

        foreach ($result as $plan) {
            $benefits[] = new BenefitsDtoRes(
                title: $plan['title'] ?? '',
                desc: $plan['desc'] ?? '',
                icon_path: $plan['icon_path'] ?? '',
            );
        }

        return $benefits;
    }
}
