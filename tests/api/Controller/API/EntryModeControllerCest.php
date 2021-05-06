<?php


namespace App\Tests\api\Controller\API;

use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class EntryModeControllerCest
{
    protected const URL = 'api/entryModes/';

    /**
     * @var ApiTester
     */
    protected $apiTester;

    public function _before(ApiTester $apiTester)
    {
        $this->apiTester = $apiTester;
//        $this->apiTester->connectApi();
    }

    public function getEntryModesByIdTest()
    {

        $this->apiTester->wantTo('get entryModes By id');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendGet(self::URL . "1");
        $this->apiTester->seeResponseCodeIs(200);
        $this->apiTester->seeResponseIsJson();

    }

    public function getEntryModesByIdNotFoundTest()
    {
        $this->apiTester->wantTo('get entryModes By id Not Found');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendGet(self::URL . "2");
        $this->apiTester->seeResponseCodeIs(HttpCode::NOT_FOUND);
    }

    public function getEntryModesList()
    {
        $this->apiTester->wantTo('get entryModes List');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendGet(self::URL);
        $this->apiTester->seeResponseCodeIs(200);
    }

    public function createEntryModesSuccessTest()
    {
        $this->apiTester->wantTo('create entryModes OK,expected Code to be ' . HttpCode::CREATED);
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendPOST(self::URL, [
            "label" => "test Label",
            "active" => true,
        ]);
        $this->apiTester->seeResponseCodeIs(HttpCode::CREATED);
        $this->apiTester->seeResponseIsJson();
    }

    public function createEntryModesFailedTest()
    {
        $this->apiTester->wantTo('create entryModes Failed,expected Code to be ' . HttpCode::BAD_REQUEST);
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendPOST(self::URL, [
            "name" => "",
        ]);
        $this->apiTester->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $this->apiTester->seeResponseIsJson();
    }

    public function updateEntryModesSuccessTest()
    {
        $this->apiTester->wantTo('Update entryModes ok,expected Code to be ' . HttpCode::CREATED);
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendPut(self::URL . "1", [
            "label" => "updated label",
        ]);
        $this->apiTester->seeResponseCodeIs(HttpCode::NO_CONTENT);
    }

    public function updateEntryModesFailedTest()
    {
        $this->apiTester->wantTo('Update entryModes Failed,expected Code to be ' . HttpCode::CREATED);
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendPut(self::URL . "1", [
            "name" => "",
        ]);
        $this->apiTester->seeResponseCodeIs(HttpCode::BAD_REQUEST);
    }

    public function deleteEntryModes()
    {
        $this->apiTester->wantTo('Delete entryModes By id');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendDelete(self::URL . "1");
        $this->apiTester->seeResponseCodeIs(HttpCode::NO_CONTENT);
        $this->apiTester->sendGet(self::URL . "1");
        $this->apiTester->seeResponseCodeIs(HttpCode::NOT_FOUND);
    }
}
