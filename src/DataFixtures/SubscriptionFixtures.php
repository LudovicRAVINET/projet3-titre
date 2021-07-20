<?php

namespace App\DataFixtures;

use App\Entity\Subscription;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class SubscriptionFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $freeSubscription = new Subscription();
        $freeSubscription->setName('GRATUIT');
        $freeSubscription->setPrice(0);
        $freeSubscription->setDescription('gratuit');
        $this->addReference('gratuit', $freeSubscription);
        $manager->persist($freeSubscription);

        $premiumSubscription = new Subscription();
        $premiumSubscription->setName('Premium');
        $premiumSubscription->setPrice(10);
        $premiumSubscription->setDescription('Premium');
        $manager->persist($premiumSubscription);

        $firmSubscription = new Subscription();
        $firmSubscription->setName('Entreprise');
        $firmSubscription->setPrice(20);
        $firmSubscription->setDescription('Entreprise');
        $manager->persist($firmSubscription);

        $manager->flush();
    }
}
