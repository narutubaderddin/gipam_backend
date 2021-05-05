<?php


namespace App\Tests\api\Controller\API;

use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class CorrespondentControllerCest
{
    protected const URL = 'api/correspondents/';

    /**
     * @var ApiTester
     */
    protected $apiTester;

    public function _before(ApiTester $apiTester)
    {
        $this->apiTester = $apiTester;
//        $this->apiTester->connectApi();
    }

    public function getCorrespondentByIdTest()
    {

        $this->apiTester->wantTo('get Correspondent By id');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendGet(self::URL . "1");
        $this->apiTester->seeResponseCodeIs(200);
        $this->apiTester->seeResponseIsJson();

    }

    public function getCorrespondentByIdNotFoundTest()
    {
        $this->apiTester->wantTo('get Correspondent By id Not Found');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendGet(self::URL . "2");
        $this->apiTester->seeResponseCodeIs(HttpCode::NOT_FOUND);
    }

    public function getCorrespondentList()
    {
        $this->apiTester->wantTo('get Correspondent List');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendGet(self::URL);
        $this->apiTester->seeResponseCodeIs(200);
    }

    public function createCorrespondentSuccessTest()
    {
        $this->apiTester->wantTo('create Correspondent OK,expected Code to be ' . HttpCode::CREATED);
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendPOST(self::URL, [
            "firstName" => "test Label",
            "startDate" => "2021-04-22T14:13:22",
        ]);
        $this->apiTester->seeResponseCodeIs(HttpCode::CREATED);
        $this->apiTester->seeResponseIsJson();
    }

    public function createCorrespondentFailedTest()
    {
        $this->apiTester->wantTo('create Correspondent Failed,expected Code to be ' . HttpCode::BAD_REQUEST);
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendPOST(self::URL, [
            "name" => "",
        ]);
        $this->apiTester->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $this->apiTester->seeResponseIsJson();
    }

    public function updateCorrespondentSuccessTest()
    {
        $this->apiTester->wantTo('Update Correspondent ok,expected Code to be ' . HttpCode::CREATED);
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendPut(self::URL . "1", [
            "firstName" => "updated firstName",
        ]);
        $this->apiTester->seeResponseCodeIs(HttpCode::NO_CONTENT);
    }

    public function updateCorrespondentFailedTest()
    {
        $this->apiTester->wantTo('Update Correspondent Failed,expected Code to be ' . HttpCode::CREATED);
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendPut(self::URL . "1", [
            "name" => "",
        ]);
        $this->apiTester->seeResponseCodeIs(HttpCode::BAD_REQUEST);
    }

    public function deleteCorrespondent()
    {
        $this->apiTester->wantTo('Delete Correspondent By id');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendDelete(self::URL . "1");
        $this->apiTester->seeResponseCodeIs(HttpCode::NO_CONTENT);
        $this->apiTester->sendGet(self::URL . "1");
        $this->apiTester->seeResponseCodeIs(HttpCode::NOT_FOUND);
    }
}
