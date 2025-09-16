<?php

namespace App\Aplication\UseCase\ResourceUseCase;

use App\Domain\Exception\Message\MessageException;
use App\Domain\Resource\v1_resourses\DatabaseResources;
use App\Domain\Service\ResourceService\ResourceSyncInterface;

class CommandResourceUseCase
{
    public function __construct(
        private ResourceSyncInterface $resourceSync,
        private DatabaseResources $databaseResources,
    ) {
    }

    public function saveResources(): void
    {
        $this->sync('языки', [$this->databaseResources, 'getLanguages'], [$this->resourceSync, 'setLanguage']);
        $this->sync('переводы', [$this->databaseResources, 'getTranslates'], [$this->resourceSync, 'setTranslation']);
        $this->sync('планы', [$this->databaseResources, 'getPremiumPlans'], [$this->resourceSync, 'setPremiumPlans']);
        $this->sync('шаблоны привычек', [$this->databaseResources, 'getHabitsTemplates'], [$this->resourceSync, 'setHabitsTemplates']);
        $this->sync('FAQ', [$this->databaseResources, 'getFaq'], [$this->resourceSync, 'setFaq']);
        $this->sync('бенефиты', [$this->databaseResources, 'getBenefits'], [$this->resourceSync, 'setBenefits']);
    }

    private function sync(string $name, callable $getData, callable $setData): void
    {
        $data = $getData();
        $result = $setData($data);

        if (empty($result)) {
            throw new MessageException("Не удалось сохранить $name, результат: ".json_encode($result));
        }
    }
}
