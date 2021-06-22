<?php

namespace App\DataFixtures;

use App\Entity\Gender;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class GenderFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $man = new Gender();
        $man->setName('Homme');
        $manager->persist($man);
        $this->addReference('Homme', $man);

        $woman = new Gender();
        $woman->setName('Femme');
        $manager->persist($woman);
        $this->addReference('Femme', $woman);

        $undef = new Gender();
        $undef->setName('Non précisé');
        $manager->persist($undef);
        $this->addReference('Undef', $undef);

        $manager->flush();
    }
}
