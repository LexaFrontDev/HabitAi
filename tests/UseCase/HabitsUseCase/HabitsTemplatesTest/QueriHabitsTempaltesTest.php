<?php

namespace App\Tests\UseCase\HabitsUseCase\HabitsTemplatesTest;

use App\Aplication\Dto\HabitsDto\HabitsTemplates\HabitTemplatesList;
use App\Aplication\UseCase\HabitsUseCase\HabitsTemplates\QueryHabitsTemplates;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class QueriHabitsTempaltesTest extends KernelTestCase
{
    private QueryHabitsTemplates $queryHabitsTemplates;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->queryHabitsTemplates = self::getContainer()->get(QueryHabitsTemplates::class);
    }

    public function testGetAllTemplates(): void
    {
        // arrange

        // act
        $result = $this->queryHabitsTemplates->getAllTemplates();

        // assert
        $this->assertIsArray($result);
        $this->assertNotEmpty($result, 'Ожидался непустой массив шаблонов');

        foreach ($result as $item) {
            $this->assertInstanceOf(HabitTemplatesList::class, $item);
        }
    }
}
