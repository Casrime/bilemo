<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Entity\Client;
use App\Tests\BaseApplication;

final class ClientTest extends BaseApplication
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
            ],
        ]);
        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/api/contexts/Client',
            '@id' => '/api/clients',
            '@type' => 'Collection',
            'totalItems' => 2,
        ]);

        /** @var array<int, array<string, mixed>> $member */
        $member = $response->toArray()['member'];

        $this->assertCount(2, $member);
        $this->assertMatchesResourceCollectionJsonSchema(Client::class);
    }

    public function testClientGetItemWithLogin(): void
    {
        $token = $this->login();
        $response = static::createClient()->request('GET', '/api/clients/1', [
            'headers' => [
                'Authorization' => 'Bearer '.$token,
            ],
        ]);
        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/api/contexts/Client',
            '@id' => '/api/clients/1',
            '@type' => 'Client',
            'id' => 1,
        ]);

        /** @var string $identifier */
        $identifier = $response->toArray()['@id'];

        $this->assertMatchesRegularExpression('~^/api/clients/\d+$~', $identifier);
    }
}
