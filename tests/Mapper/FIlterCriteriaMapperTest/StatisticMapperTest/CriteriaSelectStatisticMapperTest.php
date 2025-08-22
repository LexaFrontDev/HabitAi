<?php

namespace App\Tests\Mapper\FilterCriteriaMapperTest\StatisticMapperTest;

use App\Aplication\Dto\FiltersWithCriterias\Habits\FilterSelectStatistics;
use App\Aplication\Mapper\FilterCriteriaMappers\StatisticMapper\CriteriaSelectStatisticMapper;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CriteriaSelectStatisticMapperTest extends KernelTestCase
{
    private CriteriaSelectStatisticMapper $criteriaSelectStatisticMapper;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->criteriaSelectStatisticMapper = self::getContainer()->get(CriteriaSelectStatisticMapper::class);
    }

    public function testMakeDto(): void
    {
        $dto = $this->criteriaSelectStatisticMapper->mapToDto(
            statisticId: 1,
            userId: 2,
            name: 'habits',
            year: ['2025', '2023'],
            limit: 10,
            offset: 10
        );

        $this->assertInstanceOf(FilterSelectStatistics::class, $dto);
        $this->assertEquals(1, $dto->habitId->id);
        $this->assertEquals(2, $dto->userId->id);
        $this->assertEquals('habits', $dto->name->equal);
        $this->assertEquals(['2025', '2023'], $dto->year->in);
        $this->assertEquals(10, $dto->pagination->limit);
        $this->assertEquals(10, $dto->pagination->offset);
    }
}
