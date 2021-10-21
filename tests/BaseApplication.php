<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class BaseApplication extends ApiTestCase
{
    protected function login(): string
    {
        $response = static::createClient()->request('POST', '/api/login_check', [
            'json' => [
                'username' => 'sfr',
                'password' => 'pass',
            ],
        ])->toArray();

        return $response['token'];
    }
}
