<?php

namespace App\Tests\api\Controller\API;

use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class AttachmentTypeControllerCest
{
    protected const URL = 'api/attachmentTypes/';

    /**
     * @var ApiTester
     */
    protected $apiTester;

    public function _before(ApiTester $apiTester)
    {
        $this->apiTester = $apiTester;
        $this->apiTester->connectApi();
    }

    public function getAttachmentTypeByIdTest()
    {
        $this->apiTester->wantTo('get Attachment Types By id');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendGet(self::URL . "1");
        $this->apiTester->seeResponseCodeIs(200);
        $this->apiTester->seeResponseIsJson();
    }

    public function getAttachmentTypeByIdNotFoundTest()
    {
        $this->apiTester->wantTo('get Attachment Types By id Not Found');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendGet(self::URL . "2");
        $this->apiTester->seeResponseCodeIs(HttpCode::NOT_FOUND);
    }

    public function getAttachmentTypesList()
    {
        $this->apiTester->wantTo('get Attachment Types List');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendGet(self::URL);
        $this->apiTester->seeResponseCodeIs(200);
    }

    public function createAttachmentTypeSuccessTest()
    {
        $this->apiTester->wantTo('create Attachment Type ok,expected Code to be ' . HttpCode::CREATED);
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendPOST(self::URL, [
            "type" => "test Type",
            "active" => 1,
        ]);
        $this->apiTester->seeResponseCodeIs(HttpCode::CREATED);
        $this->apiTester->seeResponseIsJson();
    }

    public function createAttachmentTypeFailedTest()
    {
        $this->apiTester->wantTo('create Attachment Type failed,expected Code to be ' . HttpCode::BAD_REQUEST);
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendPOST(self::URL, [
            "type" => "",
            "active" => 1,
        ]);
        $this->apiTester->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $this->apiTester->seeResponseIsJson();
    }

    public function updateAttachmentTypeSuccessTest()
    {
        $this->apiTester->wantTo('update Attachment Type ok,expected Code to be ' . HttpCode::CREATED);
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendPut(self::URL . "1", [
            "type" => "updated Type",
            "active" => 0,
        ]);
        $this->apiTester->seeResponseCodeIs(HttpCode::NO_CONTENT);
    }

    public function updateAttachmentTypeFailedTest()
    {
        $this->apiTester->wantTo('update Attachment Type failed,expected Code to be ' . HttpCode::CREATED);
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendPut(self::URL . "1", [
            "type" => "",
            "active" => 0,
        ]);
        $this->apiTester->seeResponseCodeIs(HttpCode::BAD_REQUEST);
    }

    public function deleteAttachmentType()
    {
        $this->apiTester->wantTo('delete Attachment Types By id');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendDelete(self::URL . "1");
        $this->apiTester->seeResponseCodeIs(HttpCode::NO_CONTENT);
        $this->apiTester->sendGet(self::URL . "1");
        $this->apiTester->seeResponseCodeIs(HttpCode::NOT_FOUND);
    }
}
