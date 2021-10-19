<?php

namespace App\DataFixtures;

use App\Entity\Client;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ClientFixtures extends Fixture
{
    private $clients = ['sfr', 'free'];

    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoder;

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->clients as $item) {
            $client =  new Client();
            $client->setUsername($item);
            $pass = 'pass';
            $password = $this->userPasswordEncoder->encodePassword($client, $pass);
            $client->setPassword($password);
            $client->setRoles($client->getRoles());

            $this->addReference($item, $client);

            $manager->persist($client);
        }
        $manager->flush();
    }
}
