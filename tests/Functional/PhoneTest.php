<?php

namespace App\Tests\Functional;

use App\Entity\Phone;
use App\Tests\BaseApplication;

class PhoneTest extends BaseApplication
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
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 1,
        ]);

        $this->assertCount(1, $response->toArray()['hydra:member']);
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

        $this->assertMatchesRegularExpression('~^/api/phones/\d+$~', $response->toArray()['@id']);
    }
}
