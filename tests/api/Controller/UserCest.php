<?php


namespace App\Tests\api\Controller;


use App\Tests\ApiTester;

class UserCest
{

    public function _before(ApiTester $apiTester)
    {
        $apiTester->connectApi();
    }

}
