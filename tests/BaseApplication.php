<?php

declare(strict_types=1);

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

abstract class BaseApplication extends ApiTestCase
{
    protected function login(): string
    {
        $response = static::createClient()->request('POST', '/api/login_check', [
            'json' => [
                'username' => 'sfr',
                'password' => 'pass',
            ],
        ])->toArray();

        if (!isset($response['token'])) {
            throw new \RuntimeException('Token not found');
        }

        /** @var string $token */
        $token = $response['token'];

        return $token;
    }
}
