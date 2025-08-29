<?php

namespace App\Aplication\UseCase\Diffrent\FaqUseCase;

use App\Aplication\Dto\FaqsDto\FaqDtoRes;
use App\Domain\Exception\NotFoundException\NotFoundException;
use App\Domain\Service\QueriFilterInterface\FilterInterface;

class QueryFaqUseCases
{
    public function __construct(
        private FilterInterface $filter,
    ) {
    }

    /**
     * @return FaqDtoRes[]
     */
    public function getAllFaqs(): array
    {
        $obj = $this->filter->initFilter(
            criteriasDto: null,
            tableName: 'faqs',
            alias: 'f',
            select: '*'
        );
        $result = $obj->getList();

        if (empty($result)) {
            throw new NotFoundException('Не удалось получить список планов');
        }
        $faqs = [];

        foreach ($result as $faq) {
            $faqs[] = new FaqDtoRes(
                question: $faq['question'] ?? '',
                answer: $faq['answer'] ?? '',
            );
        }

        return $faqs;
    }
}
