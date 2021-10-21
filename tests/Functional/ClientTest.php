<?php

namespace App\Tests\Functional;

use App\Tests\BaseApplication;

class ClientTest extends BaseApplication
{
    public function testClientGetCollectionWithoutAuthentication(): void
    {
        static::createClient()->request('GET', '/api/clients');
        $this->assertResponseStatusCodeSame(401);
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
    }

    public function testClientGetCollectionWithLogin(): void
    {
        $token = $this->login();
        $response = static::createClient()->request('GET', '/api/clients', [
            'headers' => [
                'Authorization' => 'Bearer '.$token,
            ]
        ]);
        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/api/contexts/Client',
            '@id' => '/api/clients',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 2,
        ]);

        $this->assertCount(2, $response->toArray()['hydra:member']);
    }

    public function testClientGetItemWithLogin(): void
    {
        $token = $this->login();
        $response = static::createClient()->request('GET', '/api/clients/1', [
            'headers' => [
                'Authorization' => 'Bearer '.$token,
            ]
        ]);
        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/api/contexts/Client',
            '@id' => '/api/clients/1',
            '@type' => 'Client',
            'id'=> 1,
        ]);

        $this->assertMatchesRegularExpression('~^/api/clients/\d+$~', $response->toArray()['@id']);
    }
}
