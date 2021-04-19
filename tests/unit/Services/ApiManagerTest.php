<?php

namespace App\Tests\unit\Services;

use App\Entity\Field;
use App\Model\ApiResponse;
use App\Repository\FieldRepository;
use App\Services\ApiManager;
use App\Tests\unit\ReflectionTrait;
use App\Tests\UnitTester;
use Codeception\Test\Unit;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcher;

class ApiManagerTest extends Unit
{
    use ReflectionTrait;

    /**
     * @var UnitTester
     */
    protected $tester;

    protected $em;

    /**
     * @var ApiManager
     */
    protected $apiManager;

    /**
     * @var ParamFetcher
     */
    protected $paramFetcher;

    protected function _before()
    {
        $this->em = $this->createMock(EntityManagerInterface::class);
        $this->apiManager = new ApiManager($this->em);
    }

    public function testSave()
    {
        $field = new Field();
        $this->em
            ->expects($this->once())
            ->method('persist')
            ->with($field)
        ;
        $this->em
            ->expects($this->once())
            ->method('flush')
        ;

        $this->apiManager->save($field, true);
    }

    public function testDelete()
    {
        $field = new Field();
        $this->em
            ->expects($this->once())
            ->method('remove')
            ->with($field)
        ;
        $this->em
            ->expects($this->once())
            ->method('flush')
        ;

        $this->apiManager->delete($field, true);
    }

    public function testGetOffsetFromPageNumber()
    {
        $page = 5;
        $limit = 10;

        $result = $this->invokeMethod(
            $this->apiManager,
            'getOffsetFromPageNumber',
            [$page, $limit]
        );
        $this->assertEquals(40, $result);

        $page = 1;
        $result = $this->invokeMethod(
            $this->apiManager,
            'getOffsetFromPageNumber',
            [$page, $limit]
        );

        $this->assertEquals(0, $result);
    }

    public function testGetCriteriaFromParamFetcher()
    {
        $this->createParamFetcherMock();
        $result = $this->invokeMethod(
            $this->apiManager,
            'getCriteriaFromParamFetcher',
            [$this->paramFetcher]
        );
        $this->assertIsArray($result);
        $this->assertArrayHasKey('test', $result);
        $this->assertEquals(10, $result['test']);
    }

    public function testFindRecordsByEntityName()
    {
        $fqcn = Field::class;
        $repo = $this->createMock(FieldRepository::class);
        $this->createParamFetcherMock();

        $this->em
            ->expects($this->once())
            ->method('getRepository')
            ->with($fqcn)
            ->willReturn($repo)
        ;
        $repo
            ->expects($this->once())
            ->method('countByCriteria')
            ->willReturn(4)
        ;
        $repo
            ->expects($this->once())
            ->method('count')
            ->willReturn(6)
        ;
        $repo
            ->expects($this->once())
            ->method('findByCriteria')
            ->willReturn(array(new Field()))
        ;
        $apiResponse = $this->apiManager->findRecordsByEntityName($fqcn, $this->paramFetcher);

        $this->assertInstanceOf(ApiResponse::class, $apiResponse);
        $this->assertEquals(1, $apiResponse->getPage());
        $this->assertEquals(20, $apiResponse->getSize());
        $this->assertEquals(4, $apiResponse->getFilteredQuantity());
        $this->assertEquals(6, $apiResponse->getTotalQuantity());
        $this->assertIsArray($apiResponse->getResults());
        $this->assertCount(1, $apiResponse->getResults());
        $this->assertInstanceOf(Field::class, $apiResponse->getResults()[0]);
    }

    private function createParamFetcherMock()
    {
        $this->paramFetcher = $this->createMock(ParamFetcher::class);
        $queryParam = new QueryParam();
        $annotations = ['test' => $queryParam];
        $values = ['test' => 10];
        $this->paramFetcher
            ->expects($this->once())
            ->method('getParams')
            ->willReturn($annotations)
        ;
        $this->paramFetcher
            ->expects($this->once())
            ->method('all')
            ->willReturn($values)
        ;
        $this->paramFetcher
            ->expects($this->any())
            ->method('get')
            ->with($this->anything())
            ->willReturn($this->returnCallback(function ($param, $strict){
                if ("page" === $param && $strict)
                    return 1;
                if ("limit" === $param && $strict)
                    return 20;
                if ("sort_by" === $param)
                    return 'id';
                if ("sort" === $param)
                    return 'asc';
                if ("test" === $param)
                    return 10;
            }))
        ;
    }

}