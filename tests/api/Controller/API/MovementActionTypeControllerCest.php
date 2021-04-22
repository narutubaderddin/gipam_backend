<?php

namespace App\Tests\api\Controller\API;

use App\Entity\LocationType;
use App\Entity\MovementActionType;
use App\Tests\ApiTester;
use App\Tests\Helper\Factory;
use Codeception\Util\HttpCode;

class MovementActionTypeControllerCest
{
    protected const URL = 'api/movementActionTypes/';

    /**
     * @var ApiTester
     */
    protected $apiTester;

    /**
     * @var Factory
     */
    protected $factory;

    function beforeAllTests()
    {
        $this->factory->define(MovementActionType::class, [
            'label' => "test",
            'active' => true,
        ]);
    }

    public function _before(ApiTester $apiTester, Factory $factory)
    {
        $this->apiTester = $apiTester;
        $this->apiTester->connectApi();
        $this->factory = $factory;
    }

    public function getMovementActionTypeById()
    {
        $this->factory->have();
        $this->apiTester->wantTo('get Movement Action Type By id');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendGet(self::URL . "1");
        $this->apiTester->seeResponseCodeIs(200);
        $this->apiTester->seeResponseIsJson();
        $this->apiTester->seeResponseMatchesJsonType([
            'id' => 'integer',
            'label' => 'string',
            'active' => 'boolean',
        ]);
    }

    public function getMovementActionTypeByIdNotFound()
    {
        $this->apiTester->wantTo('get Movement Action Type By id Not Found');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendGet(self::URL . "1");
        $this->apiTester->seeResponseCodeIs(HttpCode::NOT_FOUND);
    }

    public function getMovementActionTypesList()
    {
        $this->factory->haveMultiple(2);
        $this->apiTester->wantTo('get Movement Action Types List');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendGet(self::URL);
        $this->apiTester->seeResponseCodeIs(200);
        $this->apiTester->seeResponseIsJson();
        list($totalQuantity) = $this->apiTester->grabDataFromResponseByJsonPath('$.totalQuantity');
        $this->apiTester->assertEquals(2, $totalQuantity);
    }

    public function createMovementActionTypeFailedTest()
    {
        $this->apiTester->wantTo('create Movement Action Type ,expected Code to be ' . HttpCode::BAD_REQUEST);
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendPOST(self::URL, [
            "label" => "",
            "active" => 1,
        ]);
        $this->apiTester->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $this->apiTester->seeResponseIsJson();
    }

    public function deleteLocationType()
    {
        $this->factory->have();
        $this->apiTester->wantTo('get Location Types By id');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendDelete(self::URL . "1");
        $this->apiTester->seeResponseCodeIs(HttpCode::NO_CONTENT);
        $type = $this->factory->em->getRepository(LocationType::class)->find(1);
        $this->apiTester->assertEquals(null, $type);
    }

    public function _after()
    {
        unset($this->apiTester);
        unset($this->factory);
    }
}
