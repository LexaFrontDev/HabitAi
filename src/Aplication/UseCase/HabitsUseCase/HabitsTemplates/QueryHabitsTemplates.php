<?php

namespace App\Aplication\UseCase\HabitsUseCase\HabitsTemplates;

use App\Aplication\Dto\HabitsDto\HabitsTemplates\HabitTemplatesList;
use App\Domain\Exception\NotFoundException\NotFoundException;
use App\Domain\Service\QueriFilterInterface\FilterInterface;

class QueryHabitsTemplates
{
    public function __construct(
        private FilterInterface $filter,
    ) {
    }

    /**
     * @return HabitTemplatesList[]
     *
     * @throws NotFoundException
     */
    /**
     * @return HabitTemplatesList[]
     */
    public function getAllTemplates(): array
    {
        $obj = $this->filter->initFilter(
            criteriasDto: null,
            tableName: 'habits_templates',
            alias: 'ht',
            select: '*'
        );

        $rows = $obj->getList();

        if (empty($rows)) {
            throw new NotFoundException('Шаблоны отсутствуют');
        }

        return array_map(
            fn (array $row) => new HabitTemplatesList(
                title: (string) $row['title_template'],
                quote: (string) $row['quote_template'],
                notification: (string) $row['notification_template'],
                datesType: (string) $row['dates_type_template'],
            ),
            $rows
        );
    }
}
