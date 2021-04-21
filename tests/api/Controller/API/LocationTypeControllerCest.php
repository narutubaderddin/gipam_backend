<?php

namespace App\Tests\api\Controller\API;

use App\Entity\Location;
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
        $this->factory->haveMultiple( 2);
        $this->apiTester->wantTo('get Location Types By id');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendGet(self::URL . "2");
        $this->apiTester->seeResponseCodeIs(200);
        $this->apiTester->seeResponseIsJson();
        $this->apiTester->seeResponseMatchesJsonType([
            'id' => 'integer',
            'label' => 'string',
            'active' => 'boolean',
        ]);
    }

    public function getLocationTypesList()
    {
        $this->apiTester->wantTo('get Location Types List');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendGet(self::URL);
        $this->apiTester->seeResponseCodeIs(200);
        $this->apiTester->seeResponseIsJson();
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

    public function _after()
    {
        unset($this->apiTester);
    }
}
