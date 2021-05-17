<?php


namespace App\Tests\api\Controller\API;


use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class DepositorControllerCest
{
    protected const URL = 'api/depositors/';

    /**
     * @var ApiTester
     */
    protected $apiTester;

    public function _before(ApiTester $apiTester)
    {
        $this->apiTester = $apiTester;
    }

    public function getDepositorTest()
    {
        $this->apiTester->wantTo('get Depositor By id');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendGet(self::URL . "1");
        $this->apiTester->seeResponseCodeIs(200);
        $this->apiTester->seeResponseIsJson();
    }

    public function getDepositorsListTest()
    {
        $this->apiTester->wantTo('get Depositor List');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendGet(self::URL);
        $this->apiTester->seeResponseCodeIs(200);
    }

    public function createDepositorTest()
    {
        $this->apiTester->wantTo('create a Depositor');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendPOST(self::URL, [
            "name" => "test Label",
            "acronym" => "test Acronym",
            "mail" => "test@test.com",
            "phone" => "55663333",
            "fax" => "55663333",
            "address" => "test",
            "city" => "test",
            "department" => "test",
            "distrib" => "test",
            "contact" => "test",
            "comment" => "test",
            "depositType" => 1,
            "startDate" => "2021-04-23T15:00:00",
        ]);
        $this->apiTester->seeResponseCodeIs(HttpCode::CREATED);
        $this->apiTester->seeResponseIsJson();
    }

    public function createDepositorFailedTest()
    {
        $this->apiTester->wantTo('create a Depositor Error');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendPOST(self::URL, [
            "name" => "",
            "startDate" => "2021-04-23T15:00:00",
        ]);
        $this->apiTester->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $this->apiTester->seeResponseIsJson();
    }

    public function updateDepositorTest()
    {
        $this->apiTester->wantTo('update Depositor');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendPut(self::URL . "1", [
            "name" => "updated name",
        ]);
        $this->apiTester->seeResponseCodeIs(HttpCode::NO_CONTENT);
    }

    public function updateDepositorFailedTest()
    {
        $this->apiTester->wantTo('update Depositor error');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendPut(self::URL . "1", [
            "name" => "",
        ]);
        $this->apiTester->seeResponseCodeIs(HttpCode::BAD_REQUEST);
    }

    public function deleteDepositorTest()
    {
        $this->apiTester->wantTo('delete a Depositor');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendDelete(self::URL . "1");
        $this->apiTester->seeResponseCodeIs(HttpCode::NO_CONTENT);
        $this->apiTester->sendGet(self::URL . "1");
        $this->apiTester->seeResponseCodeIs(HttpCode::NOT_FOUND);
    }
}