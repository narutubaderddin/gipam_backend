<?php


namespace App\Tests\api\Controller\API;


use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class FieldControllerTest
{
    public function getFieldsList(ApiTester $I){
        $I->wantTo('get Fields List');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGet('api/field/list');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $apiTester
     */
    public function createFieldSuccessTest(ApiTester $apiTester){
        $apiTester->wantTo('create Domaine ,expected Code to be '.HttpCode::CREATED);
        $apiTester->haveHttpHeader('Content-Type', 'application/json');
        $apiTester->sendPOST('api/field/add', [
            "label" => "test Label",
        ]);
        $apiTester->seeResponseCodeIs(HttpCode::CREATED);
        $apiTester->seeResponseIsJson();
    }



}