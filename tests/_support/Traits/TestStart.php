<?php
/**
 * Created by PhpStorm.
 * User: ebensaid
 * Date: 21/03/2019
 * Time: 17:07
 */

namespace App\Tests\_support\Traits;

use App\Tests\ApiTester;

trait TestStart
{
    public function startTest($wantMsg, $method, $route, $code, $reqBody= [])
    {
        $this->wantTo($wantMsg);
        $sendMethod ='send'.strtoupper($method);
        $this->$sendMethod($route, $reqBody);
        $this->seeResponseCodeIs($code);
        $this->seeResponseIsJson();
    }
}