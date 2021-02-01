<?php

namespace App\Tests\_support\Traits;

trait Connexion
{
    public $token;

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
