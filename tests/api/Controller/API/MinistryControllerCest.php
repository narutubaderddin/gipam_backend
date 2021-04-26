<?php


namespace App\Tests\api\Controller\API;


use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class MinistryControllerCest
{
    protected const URL = 'api/ministries/';

    /**
     * @var ApiTester
     */
    protected $apiTester;

    public function _before(ApiTester $apiTester)
    {
        $this->apiTester = $apiTester;
    }

    public function getMinistryTest()
    {
        $this->apiTester->wantTo('get Ministry By id');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendGet(self::URL . "1");
        $this->apiTester->seeResponseCodeIs(200);
        $this->apiTester->seeResponseIsJson();
    }

    public function getMinistryListTest()
    {
        $this->apiTester->wantTo('get Ministry List');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendGet(self::URL);
        $this->apiTester->seeResponseCodeIs(200);
    }

    public function createMinistryTest()
    {
        $this->apiTester->wantTo('create a Ministry');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendPOST(self::URL, [
            "name" => "test name",
            "acronym" => "test acronym",
            "startDate" => "2021-04-23T15:00:00",
        ]);
        $this->apiTester->seeResponseCodeIs(HttpCode::CREATED);
        $this->apiTester->seeResponseIsJson();
    }

    public function updateMinistryTest()
    {
        $this->apiTester->wantTo('update Ministry');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendPut(self::URL . "1", [
            "name" => "updated name",
        ]);
        $this->apiTester->seeResponseCodeIs(HttpCode::NO_CONTENT);
    }

    public function deleteMinistryTest()
    {
        $this->apiTester->wantTo('delete a Ministry');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendDelete(self::URL . "1");
        $this->apiTester->seeResponseCodeIs(HttpCode::NO_CONTENT);
        $this->apiTester->sendGet(self::URL . "1");
        $this->apiTester->seeResponseCodeIs(HttpCode::NOT_FOUND);
    }
}