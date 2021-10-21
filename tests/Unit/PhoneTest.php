<?php

namespace App\Tests\Unit;

use App\Entity\Phone;
use App\Tests\BaseApplication;

class PhoneTest extends BaseApplication
{
    private $phone;

    public function setUp(): void
    {
        $this->phone = new Phone();
    }

    public function testName(): void
    {
        $this->phone->setName('iphone');
        $this->assertEquals('iphone', $this->phone->getName());
    }

    public function testPrice(): void
    {
        $this->phone->setPrice(1000);
        $this->assertGreaterThan(0, $this->phone->getPrice());
        $this->assertEquals(1000, $this->phone->getPrice());
    }

    public function testColor(): void
    {
        $this->phone->setColor('black');
        $this->assertEquals('black', $this->phone->getColor());
    }

    public function testDescription(): void
    {
        $this->phone->setDescription('Hello from Bilemo');
        $this->assertEquals('Hello from Bilemo', $this->phone->getDescription());
    }

    public function tearDown(): void
    {
        $this->phone = null;
    }
}
