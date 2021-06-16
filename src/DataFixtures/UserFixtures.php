<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class UserFixtures extends AppFixtures
{
    public function load(ObjectManager $manager)
    {
        $adminUser = new User();
        $regularUser = new User();

        $adminPassword = $this->encoder->encodePassword($adminUser, 'admin');
        $adminUser->setEmail('admin@eventoo.fr');
        $adminUser->setPassword($adminPassword);
        $adminUser->setFirstname('Administrator');
        $adminUser->setLastname('Administrator');
        $adminUser->setRoles(['ROLE_ADMIN']);

        $userPassword = $this->encoder->encodePassword($regularUser, 'password');
        $regularUser->setEmail('user@eventoo.fr');
        $regularUser->setPassword($userPassword);
        $regularUser->setFirstname('John');
        $regularUser->setLastname('Doe');

        $manager->persist($adminUser);
        $manager->persist($regularUser);

        $manager->flush();
    }
}
