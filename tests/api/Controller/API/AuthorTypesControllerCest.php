<?php


namespace App\Tests\api\Controller\API;

use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class AuthorTypesControllerCest
{
    protected const URL = 'api/authorTypes/';

    /**
     * @var ApiTester
     */
    protected $apiTester;

    public function _before(ApiTester $apiTester)
    {
        $this->apiTester = $apiTester;
//        $this->apiTester->connectApi();
    }

    public function getAuthorTypeByIdTest()
    {

        $this->apiTester->wantTo('get AuthorType By id');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendGet(self::URL . "1");
        $this->apiTester->seeResponseCodeIs(200);
        $this->apiTester->seeResponseIsJson();

    }

    public function getAuthorTypeByIdNotFoundTest()
    {
        $this->apiTester->wantTo('get AuthorType By id Not Found');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendGet(self::URL . "2");
        $this->apiTester->seeResponseCodeIs(HttpCode::NOT_FOUND);
    }

    public function getAuthorTypeList()
    {
        $this->apiTester->wantTo('get AuthorType List');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendGet(self::URL);
        $this->apiTester->seeResponseCodeIs(200);
    }

    public function createAuthorTypeSuccessTest()
    {
        $this->apiTester->wantTo('create AuthorType OK,expected Code to be ' . HttpCode::CREATED);
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendPOST(self::URL, [
            "label" => "test Label",
            "active" => true,
        ]);
        $this->apiTester->seeResponseCodeIs(HttpCode::CREATED);
        $this->apiTester->seeResponseIsJson();
    }

    public function createAuthorTypeFailedTest()
    {
        $this->apiTester->wantTo('create AuthorType Failed,expected Code to be ' . HttpCode::BAD_REQUEST);
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendPOST(self::URL, [
            "name" => "",
        ]);
        $this->apiTester->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $this->apiTester->seeResponseIsJson();
    }

    public function updateAuthorTypeSuccessTest()
    {
        $this->apiTester->wantTo('Update AuthorType ok,expected Code to be ' . HttpCode::CREATED);
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendPut(self::URL . "1", [
            "label" => "updated label",
        ]);
        $this->apiTester->seeResponseCodeIs(HttpCode::NO_CONTENT);
    }

    public function updateAuthorTypeFailedTest()
    {
        $this->apiTester->wantTo('Update AuthorType Failed,expected Code to be ' . HttpCode::CREATED);
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendPut(self::URL . "1", [
            "name" => "",
        ]);
        $this->apiTester->seeResponseCodeIs(HttpCode::BAD_REQUEST);
    }

    public function deleteAuthorType()
    {
        $this->apiTester->wantTo('Delete AuthorType By id');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendDelete(self::URL . "1");
        $this->apiTester->seeResponseCodeIs(HttpCode::NO_CONTENT);
        $this->apiTester->sendGet(self::URL . "1");
        $this->apiTester->seeResponseCodeIs(HttpCode::NOT_FOUND);
    }
}
