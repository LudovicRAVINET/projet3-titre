<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        $maxFakeUsers = 5;
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < $maxFakeUsers; $i++) {
            $user = new User();
            $user->setEmail($faker->email);
            $user->setPassword($this->encoder->encodePassword($user, $faker->password()));
            $user->setFirstname($faker->lastName);
            $user->setLastname($faker->firstName);

            $manager->persist($user);
        }


        $manager->flush();
    }
}
