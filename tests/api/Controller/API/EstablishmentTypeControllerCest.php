<?php

namespace App\Tests\api\Controller\API;

use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class EstablishmentTypeControllerCest
{
    protected const URL = 'api/establishmentTypes/';

    /**
     * @var ApiTester
     */
    protected $apiTester;

    public function _before(ApiTester $apiTester)
    {
        $this->apiTester = $apiTester;
    }

    public function getEstablishmentTypeTest()
    {
        $this->apiTester->wantTo('get Establishment Type By id');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendGet(self::URL . "1");
        $this->apiTester->seeResponseCodeIs(200);
        $this->apiTester->seeResponseIsJson();
    }

    public function getEstablishmentTypesListTest()
    {
        $this->apiTester->wantTo('get Establishment Type List');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendGet(self::URL);
        $this->apiTester->seeResponseCodeIs(200);
        $this->apiTester->seeResponseMatchesJsonType([
            'page' => 'integer',
            'size' => 'integer',
            'filteredQuantity' => 'integer',
            'totalQuantity' => 'integer',
            'results' => [['id' => 'integer',
                'label' => 'string',
                'startDate' => 'string:date',
                'disappearanceDate' => 'string:date'
            ]],
        ]);
    }

    public function createEstablishmentTypeTest()
    {
        $this->apiTester->wantTo('create a Establishment Type');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendPOST(self::URL, [
            "label" => "test Label",
            "startDate" => "2021-04-23T15:00:00",
        ]);
        $this->apiTester->seeResponseCodeIs(HttpCode::CREATED);
        $this->apiTester->seeResponseIsJson();
    }

    public function createEstablishmentTypeFailedTest()
    {
        $this->apiTester->wantTo('create an Establishment Type Error');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendPOST(self::URL, [
            "label" => "",
            "startDate" => "2021-04-23T15:00:00",
        ]);
        $this->apiTester->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $this->apiTester->seeResponseIsJson();
    }

    public function updateEstablishmentTypeTest()
    {
        $this->apiTester->wantTo('update Establishment Type');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendPut(self::URL . "1", [
            "label" => "updated Label",
        ]);
        $this->apiTester->seeResponseCodeIs(HttpCode::NO_CONTENT);
    }

    public function updateEstablishmentTypeFailedTest()
    {
        $this->apiTester->wantTo('update Establishment Type error');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendPut(self::URL . "1", [
            "label" => "",
        ]);
        $this->apiTester->seeResponseCodeIs(HttpCode::BAD_REQUEST);
    }

    public function deleteEstablishmentTypeTest()
    {
        $this->apiTester->wantTo('delete a Establishment Type');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendDelete(self::URL . "1");
        $this->apiTester->seeResponseCodeIs(HttpCode::NO_CONTENT);
        $this->apiTester->sendGet(self::URL . "1");
        $this->apiTester->seeResponseCodeIs(HttpCode::NOT_FOUND);
    }
}