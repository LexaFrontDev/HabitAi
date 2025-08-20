<?php

namespace App\Tests\Repository\EngineTest;

use App\Aplication\CriteriaFilters\EntityFindById;
use App\Aplication\CriteriaFilters\EntityFindByLike;
use App\Aplication\CriteriaFilters\EntityJoinCriteria;
use App\Aplication\CriteriaFilters\JoinType;
use App\Aplication\Dto\ConditionsDto\OnConditionDto;
use App\Domain\Service\QueriFilterInterface\FilterInterface;
use App\Infrastructure\Service\FilterCriteriaApply\ApplyFilter;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ApplyEngineTest extends KernelTestCase
{
    private ApplyFilter $engine;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->engine = self::getContainer()->get(FilterInterface::class);
    }

    public function testTddEngineWithTwoLikes(): void
    {
        $criteria = (object) [
            'id'  => new EntityFindById(id: 1),
            'email' => new EntityFindByLike(notLike: '@gmail.com'),
            'habits' => new EntityJoinCriteria(nameTable: 'habits', nameAlias: 'h', select: ['*'], joinType: JoinType::LEFT, onCondition: [new OnConditionDto(expr: 'h.user_id = u.id', type: 'OR')]),
            'tasks' => new EntityJoinCriteria(nameTable: 'tasks', nameAlias: 't', select: ['*'], joinType: JoinType::LEFT, onCondition: [new OnConditionDto(expr: 'h.user_id = u.id', type: 'OR')]),
        ];

        $result =  $this->engine->initFilter(criteriasDto: $criteria, tableName: 'users', alias: 'u', select: '*');

        $sql = $result->getSql();


        fwrite(STDOUT, "\nGenerated SQL:\n".$sql."\n");
        fwrite(STDOUT, "Params:\n".json_encode($result->getParameter(), JSON_PRETTY_PRINT)."\n");
        $list = $result->getList();
        fwrite(STDOUT, "\nResult:\n".json_encode($list, JSON_PRETTY_PRINT)."\n");
        $this->assertCount(0, $list);
    }
}
