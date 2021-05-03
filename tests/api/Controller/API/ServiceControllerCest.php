<?php


namespace App\Tests\api\Controller\API;


use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class ServiceControllerCest
{
    protected const URL = 'api/services/';

    /**
     * @var ApiTester
     */
    protected $apiTester;

    public function _before(ApiTester $apiTester)
    {
        $this->apiTester = $apiTester;
    }

    public function getServiceTest()
    {
        $this->apiTester->wantTo('get Service By id');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendGet(self::URL . "1");
        $this->apiTester->seeResponseCodeIs(200);
        $this->apiTester->seeResponseIsJson();
    }

    public function getServiceListTest()
    {
        $this->apiTester->wantTo('get Service List');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendGet(self::URL);
        $this->apiTester->seeResponseCodeIs(200);
    }

    public function createServiceTest()
    {
        $this->apiTester->wantTo('create a Service');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendPOST(self::URL, [
            "label" => "test Label",
            "acronym" => "test Acronym",
            "startDate" => "2021-04-23T15:00:00",
            "subDivision" => 1,
        ]);
        $this->apiTester->seeResponseCodeIs(HttpCode::CREATED);
        $this->apiTester->seeResponseIsJson();
    }

    public function updateServiceTest()
    {
        $this->apiTester->wantTo('update Service');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendPut(self::URL . "1", [
            "label" => "updated Label",
        ]);
        $this->apiTester->seeResponseCodeIs(HttpCode::NO_CONTENT);
    }

    public function deleteServiceTest()
    {
        $this->apiTester->wantTo('delete a Service');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendDelete(self::URL . "1");
        $this->apiTester->seeResponseCodeIs(HttpCode::NO_CONTENT);
        $this->apiTester->sendGet(self::URL . "1");
        $this->apiTester->seeResponseCodeIs(HttpCode::NOT_FOUND);
    }
}