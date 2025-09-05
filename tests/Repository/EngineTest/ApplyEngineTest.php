<?php

namespace App\Tests\Repository\EngineTest;

use App\Aplication\CriteriaFilters\EntityFindById;
use App\Aplication\CriteriaFilters\EntityFindByLike;
use App\Aplication\CriteriaFilters\EntityFindByString;
use App\Aplication\CriteriaFilters\EntityJoinCriteria;
use App\Aplication\CriteriaFilters\JoinType;
use App\Aplication\Dto\ConditionsDto\OnConditionDto;
use App\Domain\Service\QueriFilterInterface\FilterInterface;
use App\Infrastructure\Service\FilterCriteriaApply\FilterApplicator\ApplyFilter;
use Doctrine\DBAL\Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ApplyEngineTest extends KernelTestCase
{
    private ApplyFilter $engine;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->engine = self::getContainer()->get(FilterInterface::class);
    }

    /**
     * @throws Exception
     */
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
        $list = $result->getList();
        $this->assertCount(0, $list);
    }

    public function testFindByIdCriteriaGeneratesCorrectSql(): void
    {
        // Arrange
        $criteria = (object) [
            'id' => new EntityFindById(id: 5),
        ];

        // Act
        $result = $this->engine->initFilter(
            criteriasDto: $criteria,
            tableName: 'users',
            alias: 'u',
            select: '*'
        );

        // Assert
        $sql = $result->getSql();
        $params = $result->getParameter();

        $this->assertStringContainsString('u.id = :id', $sql);
        $this->assertSame(['id' => 5], $params);
    }

    public function testFindByString()
    {
        // Arrange
        $criteria = (object) [
            'name' => new EntityFindByString(equal: 'Leha'),
        ];



        // Act
        $result = $this->engine->initFilter(
            criteriasDto: $criteria,
            tableName: 'users',
            alias: 'u',
            select: '*'
        );

        // Assert
        $sql = $result->getSql();
        $params = $result->getParameter();
        $this->assertStringContainsString('u.name = :name', $sql);
        $this->assertSame(['name' => 'Leha'], $params);
    }

    public function testFindByLike()
    {
        // Arrange
        $criteria = (object) [
            'email' => new EntityFindByLike(like: 'marina@gmail.com'),
        ];


        // Act
        $result = $this->engine->initFilter(
            criteriasDto: $criteria,
            tableName: 'users',
            alias: 'u',
            select: '*'
        );

        // Assert
        $sql = $result->getSql();
        $params = $result->getParameter();
        $this->assertStringContainsString('u.email LIKE :email', $sql);
        $this->assertSame(['email' => '%marina@gmail.com%'], $params);
    }

    public function testNotLike()
    {
        // Arrange
        $criteria = (object) [
            'email' => new EntityFindByLike(notLike: 'marina@gmail.com'),
        ];

        // Act
        $result = $this->engine->initFilter(
            criteriasDto: $criteria,
            tableName: 'users',
            alias: 'u',
            select: '*'
        );

        // Assert
        $sql = $result->getSql();
        $params = $result->getParameter();

        $this->assertStringContainsString('u.email NOT LIKE :not_email', $sql);
        $this->assertSame(['not_email' => '%marina@gmail.com%'], $params);
    }

    public function testAnyOf()
    {
        // Arrange
        $criteria = (object) [
            'name' => new EntityFindByString(anyOf: ['Leha', 'Marina', 'Andrey']),
        ];

        // Act
        $result = $this->engine->initFilter(
            criteriasDto: $criteria,
            tableName: 'users',
            alias: 'u',
            select: '*'
        );

        // Assert
        $sql = $result->getSql();
        $params = $result->getParameter();

        $this->assertStringContainsString('u.name = :name_any_0', $sql);
        $this->assertStringContainsString('u.name = :name_any_1', $sql);
        $this->assertStringContainsString('u.name = :name_any_2', $sql);

        $this->assertContains('Leha', $params);
        $this->assertContains('Marina', $params);
        $this->assertContains('Andrey', $params);
        $this->assertCount(3, $params);
    }

    public function testIn()
    {
        // Arrange
        $criteria = (object) [
            'name' => new EntityFindByString(in: ['Leha', 'Marina', 'Andrey']),
        ];

        // Act
        $result = $this->engine->initFilter(
            criteriasDto: $criteria,
            tableName: 'users',
            alias: 'u',
            select: '*'
        );

        // Assert
        $sql = $result->getSql();
        $params = $result->getParameter();

        $this->assertStringContainsString('u.name IN', $sql);
        $values = $params['in_name'];
        $this->assertContains('Leha', $values);
        $this->assertContains('Marina', $values);
        $this->assertContains('Andrey', $values);
        $this->assertCount(3, $values);
    }

    public function testInId()
    {
        // Arrange
        $criteria = (object) [
            'id' => new EntityFindById(in: [1, 222, 333]),
        ];

        // Act
        $result = $this->engine->initFilter(
            criteriasDto: $criteria,
            tableName: 'users',
            alias: 'u',
            select: '*'
        );

        // Assert
        $sql = $result->getSql();
        $params = $result->getParameter();
        $this->assertStringContainsString('u.id IN', $sql);
        $values = $params['in_id'];
        $this->assertContains(1, $values);
        $this->assertContains(222, $values);
        $this->assertContains(333, $values);
        $this->assertCount(3, $values);
    }

    public function testNotInId()
    {
        // Arrange
        $criteria = (object) [
            'id' => new EntityFindById(notIn: [1, 222, 333]),
        ];

        // Act
        $result = $this->engine->initFilter(
            criteriasDto: $criteria,
            tableName: 'users',
            alias: 'u',
            select: '*'
        );

        // Assert
        $sql = $result->getSql();
        $params = $result->getParameter();
        $this->assertStringContainsString('u.id NOT IN', $sql);
        $values = $params['notin_id'];
        $this->assertContains(1, $values);
        $this->assertContains(222, $values);
        $this->assertContains(333, $values);
        $this->assertCount(3, $values);
    }
}
