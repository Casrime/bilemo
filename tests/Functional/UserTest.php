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
            ],
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

    public function testUserPostCollectionWithLoginWithoutValues(): void
    {
        $token = $this->login();
        static::createClient()->request('POST', '/api/users', [
            'headers' => [
                'Authorization' => 'Bearer '.$token,
            ],
            'json' => [],
        ]);
        $this->assertResponseStatusCodeSame(422);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/api/contexts/ConstraintViolationList',
            '@type' => 'ConstraintViolationList',
            'hydra:title' => 'An error occurred',
            'hydra:description' => "firstname: Ce champ ne doit pas être vide\nlastname: Ce champ ne doit pas être vide\npseudo: Ce champ ne doit pas être vide",
            'violations' => [
                [
                    'propertyPath' => 'firstname',
                    'message' => 'Ce champ ne doit pas être vide',
                    'code' => 'c1051bb4-d103-4f74-8988-acbcafc7fdc3',
                ],
                [
                    'propertyPath' => 'lastname',
                    'message' => 'Ce champ ne doit pas être vide',
                    'code' => 'c1051bb4-d103-4f74-8988-acbcafc7fdc3',
                ],
                [
                    'propertyPath' => 'pseudo',
                    'message' => 'Ce champ ne doit pas être vide',
                    'code' => 'c1051bb4-d103-4f74-8988-acbcafc7fdc3',
                ],
            ],
        ]);
    }

    public function testUserPostCollectionWithLoginWithValidValues(): void
    {
        $token = $this->login();
        $response = static::createClient()->request('POST', '/api/users', [
            'headers' => [
                'Authorization' => 'Bearer '.$token,
            ],
            'json' => [
                'firstname' => 'mario',
                'lastname' => 'bros',
                'pseudo' => 'mario-bros',
            ],
        ]);
        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/api/contexts/User',
            '@type' => 'User',
            'firstname' => 'mario',
            'lastname' => 'bros',
            'pseudo' => 'mario-bros',
            'client' => '/api/clients/1',
        ]);

        $this->assertMatchesRegularExpression('~^/api/users/\d+$~', $response->toArray()['@id']);
        $this->assertMatchesResourceItemJsonSchema(User::class);
    }

    public function testUserGetItemWithLogin(): void
    {
        $token = $this->login();
        $response = static::createClient()->request('GET', '/api/users/1', [
            'headers' => [
                'Authorization' => 'Bearer '.$token,
            ],
        ]);
        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/api/contexts/User',
            '@id' => '/api/users/1',
            '@type' => 'User',
            'id' => 1,
            'client' => '/api/clients/1',
        ]);

        $this->assertMatchesRegularExpression('~^/api/users/\d+$~', $response->toArray()['@id']);
        $this->assertMatchesResourceItemJsonSchema(User::class);
    }
}
