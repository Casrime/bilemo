<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Entity\Phone;
use App\Tests\BaseApplication;

final class PhoneTest extends BaseApplication
{
    public function testPhoneGetCollectionWithoutAuthentication(): void
    {
        static::createClient()->request('GET', '/api/phones');
        $this->assertResponseStatusCodeSame(401);
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
    }

    public function testPhoneGetCollectionWithLogin(): void
    {
        $token = $this->login();
        $response = static::createClient()->request('GET', '/api/phones', [
            'headers' => [
                'Authorization' => 'Bearer '.$token,
            ],
        ]);
        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/api/contexts/Phone',
            '@id' => '/api/phones',
            '@type' => 'Collection',
            'totalItems' => 1,
        ]);

        $this->assertEquals(1, $response->toArray()['member']);
        $this->assertMatchesResourceCollectionJsonSchema(Phone::class);
    }

    public function testPhoneGetItemWithLogin(): void
    {
        $token = $this->login();
        $response = static::createClient()->request('GET', '/api/phones/1', [
            'headers' => [
                'Authorization' => 'Bearer '.$token,
            ],
        ]);
        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/api/contexts/Phone',
            '@id' => '/api/phones/1',
            '@type' => 'Phone',
            'id' => 1,
            'name' => 'iPhone X',
            'price' => 1000,
            'color' => 'black',
            'description' => 'Le meilleur iPhone',
        ]);

        /** @var string $identifier */
        $identifier = $response->toArray()['@id'];

        $this->assertMatchesRegularExpression('~^/api/phones/\d+$~', $identifier);
    }
}
