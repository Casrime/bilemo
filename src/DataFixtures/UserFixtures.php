<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    private $clients = ['sfr', 'free'];

    public function load(ObjectManager $manager)
    {
        for($i = 0; $i <= 20; $i++) {
            $user = new User();
            $user->setFirstname('Jean');
            $user->setLastname('Dupont');
            $user->setPseudo('jeandupont');
            $user->setBirthdayDate(new \DateTime());
            $client = rand(0, 1);
            $user->setClient($this->getReference($this->clients[$client]));

            $manager->persist($user);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ClientFixtures::class
        ];
    }

}
