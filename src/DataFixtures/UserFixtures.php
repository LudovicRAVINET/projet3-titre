<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use DateTime;

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
        $adminUser->setBirthDate(new DateTime('1975-03-22'));

        $userPassword = $this->encoder->encodePassword($regularUser, 'password');
        $regularUser->setEmail('user@eventoo.fr');
        $regularUser->setPassword($userPassword);
        $regularUser->setFirstname('John');
        $regularUser->setLastname('Doe');
        $regularUser->setBirthDate(new DateTime('1982-10-16'));
        $this->addReference('user_test', $regularUser);

        $manager->persist($adminUser);
        $manager->persist($regularUser);

        $manager->flush();
    }
}
