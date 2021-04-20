<?php

namespace App\Tests\unit\Repository;

use App\Repository\RepositoryTrait;
use App\Tests\unit\ReflectionTrait;
use App\Tests\UnitTester;
use Codeception\Test\Unit;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\QueryBuilder;

class RepositoryTraitTest extends Unit
{
    use ReflectionTrait;

    /**
     * @var UnitTester
     */
    protected $tester;

    protected $repositoryTrait;

    protected function _before()
    {
        $this->repositoryTrait = $this->getObjectForTrait(RepositoryTrait::class);
    }

    public function testCreateCriteria()
    {
        $operator = 'eq';
        $key = 'label';
        $value = 'test';

        $criteria = $this->invokeMethod(
            $this->repositoryTrait,
            'createCriteria',
            [$operator, $key, $value]
        );
        $this->assertInstanceOf(Criteria::class, $criteria);
        $this->assertEquals($criteria->getWhereExpression()->getField(), 'label');
        $this->assertEquals($criteria->getWhereExpression()->getOperator(), '=');
        $this->assertEquals($criteria->getWhereExpression()->getValue()->getValue(), 'test');
    }

    public function testAddCriteria()
    {
        $queryBuilder = $this->createMock(QueryBuilder::class);
        $criteria = [
            'active' => 1,
            'label' => ['startsWith' => 'te'],
            'label' => ['endsWith' => 't'],
            ]
        ;
        $queryBuilder
            ->expects($this->once())
            ->method('addCriteria')
            ->with($this->anything())
        ;
        $queryBuilder
            ->expects($this->once())
            ->method('getRootAliases')
            ->willReturn(['e'])
        ;
        $queryBuilder
            ->expects($this->once())
            ->method('andWhere')
            ->with($this->anything())
            ->willReturn($queryBuilder)
        ;
        $queryBuilder = $this->invokeMethod(
            $this->repositoryTrait,
            'addCriteria',
            [$queryBuilder, $criteria]
        );
        $this->assertInstanceOf(QueryBuilder::class, $queryBuilder);
    }

    public function testAndWhere()
    {
        $queryBuilder = $this->createMock(QueryBuilder::class);
        ;
        $queryBuilder
            ->expects($this->once())
            ->method('getRootAliases')
            ->willReturn(['e'])
        ;
        $queryBuilder
            ->expects($this->once())
            ->method('andWhere')
            ->with("e.label LIKE :label0")
            ->willReturn($queryBuilder)
        ;
        $queryBuilder = $this->invokeMethod(
            $this->repositoryTrait,
            'andWhere',
            [$queryBuilder, 'label', 'contains', 'label0', 'tes']
        );
        $this->assertInstanceOf(QueryBuilder::class, $queryBuilder);
    }

    public function testGetOperators()
    {
        $expected = ['eq', 'gt', 'lt', 'gte', 'lte', 'neq', 'contains', 'startsWith', 'endsWith'];
        $operators = RepositoryTrait::getOperators();
        $this->assertCount(9, $operators);
        $this->assertEquals($expected, $operators);
    }
}