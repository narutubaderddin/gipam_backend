<?php

namespace App\Tests\api\Controller\API;

use App\Entity\LocationType;
use App\Tests\ApiTester;
use App\Tests\Helper\Factory;
use Codeception\Util\HttpCode;

class LocationTypeControllerCest
{
    protected const URL = 'api/locationTypes/';

    /**
     * @var ApiTester
     */
    protected $apiTester;

    /**
     * @var Factory
     */
    protected $factory;

    public function _before(ApiTester $apiTester, Factory $factory)
    {
        $this->apiTester = $apiTester;
        $this->apiTester->connectApi();
        $this->factory = $factory;
        $this->factory->define(LocationType::class, [
            'label' => "test",
            'active' => true,
        ]);
    }

    public function getLocationTypeById()
    {
        $this->factory->have();
        $this->apiTester->wantTo('get Location Types By id');
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

    public function getLocationTypeByIdNotFound()
    {
        $this->apiTester->wantTo('get Location Types By id Not Found');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendGet(self::URL . "1");
        $this->apiTester->seeResponseCodeIs(HttpCode::NOT_FOUND);
    }

    public function getLocationTypesList()
    {
        $this->factory->haveMultiple(2);
        $this->apiTester->wantTo('get Location Types List');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendGet(self::URL);
        $this->apiTester->seeResponseCodeIs(200);
        $this->apiTester->seeResponseIsJson();
        list($totalQuantity) = $this->apiTester->grabDataFromResponseByJsonPath('$.totalQuantity');
        $this->apiTester->assertEquals(2, $totalQuantity);
    }

    public function createLocationTypeSuccessTest()
    {
        $this->apiTester->connectApi();
        $this->apiTester->wantTo('create Location Type ,expected Code to be ' . HttpCode::CREATED);
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendPOST(self::URL, [
            "label" => "test Label",
            "active" => 1,
        ]);
        $this->apiTester->seeResponseCodeIs(HttpCode::CREATED);
        $this->apiTester->seeResponseIsJson();
    }

    public function createLocationTypeFailedTest()
    {
        $this->apiTester->connectApi();
        $this->apiTester->wantTo('create Location Type ,expected Code to be ' . HttpCode::BAD_REQUEST);
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendPOST(self::URL, [
            "label" => "",
            "active" => 1,
        ]);
        $this->apiTester->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $this->apiTester->seeResponseIsJson();
    }

    public function updateLocationTypeSuccessTest()
    {
        /** @var LocationType $type */
        $type = $this->factory->have();
        $this->apiTester->connectApi();
        $this->apiTester->wantTo('create Location Type ,expected Code to be ' . HttpCode::CREATED);
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendPut(self::URL . "1", [
            "label" => "updated Label",
            "active" => 0,
        ]);
        $this->apiTester->seeResponseCodeIs(HttpCode::NO_CONTENT);
        $updatedType = $this->factory->em->getRepository(LocationType::class)->find(1);
        $this->apiTester->assertEquals("updated Label", $updatedType->getLabel());
        $this->apiTester->assertEquals(false, $updatedType->isActive());
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
