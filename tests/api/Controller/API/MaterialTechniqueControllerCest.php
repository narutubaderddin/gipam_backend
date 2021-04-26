<?php


namespace App\Tests\api\Controller\API;


use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class MaterialTechniqueControllerCest
{
    protected const URL = 'api/materialTechniques/';

    /**
     * @var ApiTester
     */
    protected $apiTester;

    public function _before(ApiTester $apiTester)
    {
        $this->apiTester = $apiTester;
    }

    public function getMaterialTechniqueTest()
    {
        $this->apiTester->wantTo('get MaterialTechnique By id');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendGet(self::URL . "1");
        $this->apiTester->seeResponseCodeIs(200);
        $this->apiTester->seeResponseIsJson();
    }

    public function getMaterialTechniquesListTest()
    {
        $this->apiTester->wantTo('get MaterialTechnique List');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendGet(self::URL);
        $this->apiTester->seeResponseCodeIs(200);
    }

    public function createMaterialTechniqueTest()
    {
        $this->apiTester->wantTo('create a MaterialTechnique');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendPOST(self::URL, [
            "label" => "test Label",
            "type" => "test Type",
            "active" => 1,
        ]);
        $this->apiTester->seeResponseCodeIs(HttpCode::CREATED);
        $this->apiTester->seeResponseIsJson();
    }

    public function updateMaterialTechniqueTest()
    {
        $this->apiTester->wantTo('update MaterialTechnique');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendPut(self::URL . "1", [
            "label" => "updated Label",
            "type" => "updated Type",
            "active" => 0,
        ]);
        $this->apiTester->seeResponseCodeIs(HttpCode::NO_CONTENT);
    }

    public function deleteMaterialTechniqueTest()
    {
        $this->apiTester->wantTo('delete a MaterialTechnique');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendDelete(self::URL . "1");
        $this->apiTester->seeResponseCodeIs(HttpCode::NO_CONTENT);
        $this->apiTester->sendGet(self::URL . "1");
        $this->apiTester->seeResponseCodeIs(HttpCode::NOT_FOUND);
    }
}