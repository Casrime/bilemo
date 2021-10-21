<?php

namespace App\Tests\Functional;

use App\Entity\User;
use App\Tests\BaseApplication;

class UserTest extends BaseApplication
{
    public function testUserGetCollectionWithoutAuthentication(): void
    {
        static::createClient()->request('GET', '/api/users');
        $this->assertResponseStatusCodeSame(401);
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
    }

    public function testUserGetCollectionWithLogin(): void
    {
        $token = $this->login();
        $response = static::createClient()->request('GET', '/api/users', [
            'headers' => [
                'Authorization' => 'Bearer '.$token,
            ]
        ]);
        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/api/contexts/User',
            '@id' => '/api/users',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 10,
        ]);

        $this->assertCount(10, $response->toArray()['hydra:member']);
    }

    public function testUserGetItemWithLogin(): void
    {
        $token = $this->login();
        $response = static::createClient()->request('GET', '/api/users/1', [
            'headers' => [
                'Authorization' => 'Bearer '.$token,
            ]
        ]);
        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/api/contexts/User',
            '@id' => '/api/users/1',
            '@type' => 'User',
            'id'=> 1,
            'client'=> '/api/clients/1',
        ]);

        $this->assertMatchesRegularExpression('~^/api/users/\d+$~', $response->toArray()['@id']);
    }
}
