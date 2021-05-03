<?php


namespace App\Tests\api\Controller\API;


use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class NoticeControllerCest
{
    protected const URL = 'api/notices/';

    /**
     * @var ApiTester
     */
    protected $apiTester;

    public function _before(ApiTester $apiTester)
    {
        $this->apiTester = $apiTester;
    }

    public function getAttributesTest()
    {
        $results =  ["title","length","width","height","depth","diameter","totalLength","totalWidth","totalHeight",
            "weight","numberOfUnit","era","style","authors","materialTechnique","status","denomination","field",
            "attachments","hyperlinks","photographies","visible","parent","createdAt"
        ];
        $this->apiTester->wantTo('get attributes for notice');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendGet(self::URL.'attributes');
        $this->apiTester->seeResponseCodeIs(200);
        $this->apiTester->canSeeResponseContainsJson($results);
    }

    public function createDepositNoticeTest()
    {
        $this->apiTester->wantTo('create a Deposit Notice');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendPOST(self::URL.'deposit', [
            "title" => "test title",
            "denomination" => 1,
            "field" => 1,
            "numberOfUnit" => 5,
            "materialTechnique" => 1,
            "status" => [
                "stopNumber" => 3,
                "depositDate" => "2021-05-03T14:13:22"
            ]
        ]);
        $this->apiTester->seeResponseCodeIs(HttpCode::CREATED);
        $this->apiTester->seeResponseIsJson();
    }

    public function createPropertyNoticeTest()
    {
        $this->apiTester->wantTo('create a Property Notice');
        $this->apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->apiTester->sendPOST(self::URL.'property', [
            "title" => "test title",
            "denomination" => 1,
            "field" => 1,
            "numberOfUnit" => 5,
            "materialTechnique" => 1,
            "status" => [
                "insuranceValueDate" => "2021-05-03T14:13:22",
                "entryDate" => "2021-05-03T14:13:22",
                "entryMode" => 1,
                "category" => 1,
            ]
        ]);
        $this->apiTester->seeResponseCodeIs(HttpCode::CREATED);
        $this->apiTester->seeResponseIsJson();
    }
}