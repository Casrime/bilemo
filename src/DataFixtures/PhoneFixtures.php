<?php

namespace App\DataFixtures;

use App\Entity\Phone;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PhoneFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $phone = new Phone();
        $phone->setName('iPhone X');
        $phone->setPrice(1000);
        $phone->setDescription('Le meilleur iPhone');
        $phone->setColor('black');
        $manager->persist($phone);

        $manager->flush();
    }
}
