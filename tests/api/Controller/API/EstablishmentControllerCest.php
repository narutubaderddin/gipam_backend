<?php


namespace App\Tests\api\Controller\API;


use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class EstablishmentControllerCest
{
    protected const URL = 'api/establishments/';

    /**
     * @var ApiTester
     */
    protected $apiTester;

    public function _before(ApiTester $apiTester)
    {
        $this->apiTester = $apiTester;
    }

    public function getEstablishmentTest()
    {
        $this->apiTester->wantTo('get Establishment By id');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendGet(self::URL . "1");
        $this->apiTester->seeResponseCodeIs(200);
        $this->apiTester->seeResponseIsJson();
    }

    public function getEstablishmentsListTest()
    {
        $this->apiTester->wantTo('get Establishment List');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendGet(self::URL);
        $this->apiTester->seeResponseCodeIs(200);
    }

    public function createEstablishmentTest()
    {
        $this->apiTester->wantTo('create a Establishment');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendPOST(self::URL, [
            "label" => "test Label",
            "acronym" => "test Acronym",
            "startDate" => "2021-04-23T15:00:00",
        ]);
        $this->apiTester->seeResponseCodeIs(HttpCode::CREATED);
        $this->apiTester->seeResponseIsJson();
    }

    public function updateEstablishmentTest()
    {
        $this->apiTester->wantTo('update Establishment');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendPut(self::URL . "1", [
            "label" => "updated Label",
        ]);
        $this->apiTester->seeResponseCodeIs(HttpCode::NO_CONTENT);
    }

    public function deleteEstablishmentTest()
    {
        $this->apiTester->wantTo('delete a Establishment');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendDelete(self::URL . "1");
        $this->apiTester->seeResponseCodeIs(HttpCode::NO_CONTENT);
        $this->apiTester->sendGet(self::URL . "1");
        $this->apiTester->seeResponseCodeIs(HttpCode::NOT_FOUND);
    }
}