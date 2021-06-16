<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $adminUser = new User();
        $regularUser = new User();

        $adminUser->setEmail('admin@eventoo.fr');
        $adminUser->setPassword('admin');
        $adminUser->setFirstname('Administrator');
        $adminUser->setLastname('Administrator');
        $adminUser->setRoles(['ROLE_ADMIN']);

        $regularUser->setEmail('user@eventoo.fr');
        $regularUser->setPassword('password');
        $regularUser->setFirstname('John');
        $regularUser->setLastname('Doe');

        $manager->persist($adminUser);
        $manager->persist($regularUser);

        $manager->flush();
    }
}
