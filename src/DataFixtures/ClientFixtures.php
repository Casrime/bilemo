<?php

namespace App\DataFixtures;

use App\Entity\Client;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ClientFixtures extends Fixture
{
    private $clients = ['sfr', 'free'];

    /**
     * @var UserPasswordHasherInterface
     */
    private $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->clients as $item) {
            $client =  new Client();
            $client->setUsername($item);
            $pass = 'pass';
            $password = $this->userPasswordHasher->hashPassword($client, $pass);
            $client->setPassword($password);
            $client->setRoles($client->getRoles());

            $this->addReference($item, $client);

            $manager->persist($client);
        }
        $manager->flush();
    }
}
