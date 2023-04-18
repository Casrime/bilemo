<?php

namespace App\Tests\Unit;

use App\Entity\Client;
use App\Entity\User;
use App\Tests\BaseApplication;

class ClientTest extends BaseApplication
{
    private $client;

    public function setUp(): void
    {
        $this->client = new Client();
    }

    public function testUsername(): void
    {
        $this->client->setUsername('mario');
        $this->assertEquals('mario', $this->client->getUsername());
    }

    public function testRoles(): void
    {
        $this->client->setRoles([]);
        $this->assertEquals(['ROLE_USER'], $this->client->getRoles());
    }

    public function testPassword(): void
    {
        $this->client->setPassword('pass');
        $this->assertEquals('pass', $this->client->getPassword());
    }

    public function testUser(): void
    {
        $user = new User();
        $this->client->addUser($user);
        $this->assertCount(1, $this->client->getUsers());
        $this->client->removeUser($user);
        $this->assertCount(0, $this->client->getUsers());
    }

    public function tearDown(): void
    {
        $this->client = null;
    }
}
