<?php
namespace App\Tests;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
*/
class ApiTester extends \Codeception\Actor
{
    use _generated\ApiTesterActions;

    /**
     * Define custom actions here
     */
    public function connectApi()
    {
        $config = \Codeception\Configuration::config();
        $apiSettings = \Codeception\Configuration::suiteSettings('api', $config);
        $this->wantTo('_before get a token');
        $this->haveHttpHeader('Content-Type', 'application/json');
        $this->sendPOST('api/login_check', [
            'username' => $apiSettings['params']['username'],
            'password' => $apiSettings['params']['password']
        ]);
        $this->token = $this->grabDataFromResponseByJsonPath('token')[0];
        $this->amBearerAuthenticated($this->token);
    }
}
