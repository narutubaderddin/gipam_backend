<?php

namespace App\Tests\api\Controller\API;

use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class MovementActionTypeControllerCest
{
    protected const URL = 'api/movementActionTypes/';

    /**
     * @var ApiTester
     */
    protected $apiTester;

    public function _before(ApiTester $apiTester)
    {
        $this->apiTester = $apiTester;
        $this->apiTester->connectApi();
    }

    public function getMovementActionTypeByIdTest()
    {
        $this->apiTester->wantTo('get Movement Action Type By id');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendGet(self::URL . "1");
        $this->apiTester->seeResponseCodeIs(200);
        $this->apiTester->seeResponseIsJson();
    }

    public function getMovementActionTypeByIdNotFoundTest()
    {
        $this->apiTester->wantTo('get Movement Action Type By id Not Found');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendGet(self::URL . "2");
        $this->apiTester->seeResponseCodeIs(HttpCode::NOT_FOUND);
    }

    public function getMovementActionTypesList()
    {
        $this->apiTester->wantTo('get Movement Action Types List');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendGet(self::URL);
        $this->apiTester->seeResponseCodeIs(200);
        $this->apiTester->seeResponseIsJson();
        list($totalQuantity) = $this->apiTester->grabDataFromResponseByJsonPath('$.totalQuantity');
        $this->apiTester->assertEquals(1, $totalQuantity);
    }

    public function createMovementActionTypeSuccessTest()
    {
        $this->apiTester->wantTo('create Movement Action Type ok,expected Code to be ' . HttpCode::CREATED);
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendPOST(self::URL, [
            "label" => "test Label",
            "movementType" => 1,
            "active" => 1,
        ]);
        $this->apiTester->seeResponseCodeIs(HttpCode::CREATED);
        $this->apiTester->seeResponseIsJson();
    }

    public function createMovementActionTypeFailedTest()
    {
        $this->apiTester->wantTo('create Movement Action Type failed,expected Code to be ' . HttpCode::BAD_REQUEST);
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendPOST(self::URL, [
            "label" => "",
            "movementType" => 1,
            "active" => 1,
        ]);
        $this->apiTester->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $this->apiTester->seeResponseIsJson();
    }

    public function updateMovementActionTypeSuccessTest()
    {
        $this->apiTester->wantTo('update Movement Action Type ok,expected Code to be ' . HttpCode::CREATED);
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendPut(self::URL . "1", [
            "label" => "updated Label",
        ]);
        $this->apiTester->seeResponseCodeIs(HttpCode::NO_CONTENT);
    }

    public function updateMovementActionTypeFailedTest()
    {
        $this->apiTester->wantTo('update Movement Action Type failed,expected Code to be ' . HttpCode::CREATED);
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendPut(self::URL . "1", [
            "label" => "",
        ]);
        $this->apiTester->seeResponseCodeIs(HttpCode::BAD_REQUEST);
    }

    public function deleteMovementActionTypeTest()
    {
        $this->apiTester->wantTo('delete Movement Action Types By id');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendDelete(self::URL . "1");
        $this->apiTester->seeResponseCodeIs(HttpCode::NO_CONTENT);
        $this->apiTester->sendGet(self::URL . "1");
        $this->apiTester->seeResponseCodeIs(HttpCode::NOT_FOUND);
    }
}
