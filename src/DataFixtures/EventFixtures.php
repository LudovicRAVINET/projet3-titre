<?php

namespace App\DataFixtures;

use App\Entity\Event;
use DateTime;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\Validator\Constraints\Date;

/**
 * @codeCoverageIgnore
 */
class EventFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        // Wedding
        $wedding = new Event();
        $wedding->setTitle('Mariage de Zoto & Choco');
        $wedding->setType($this->getReference('type_0'));
        $wedding->setUser($this->getReference('user_test'));
        $wedding->setDate(new DateTime('2021-06-13'));
        $wedding->setTime(new DateTime('12:00:00'));
        $wedding->setHasJackpot(true);

        // Birthday
        $birthday = new Event();
        $birthday->setTitle('Anniversaire de Karine');
        $birthday->setType($this->getReference('type_1'));
        $birthday->setUser($this->getReference('user_test'));
        $birthday->setDate(new DateTime('2021-06-13'));
        $birthday->setTime(new DateTime('12:00:00'));
        $birthday->setHasJackpot(true);

        // Wedding
        $wedding2 = new Event();
        $wedding2->setTitle('Mariage de Mamy & Nadia');
        $wedding2->setType($this->getReference('type_0'));
        $wedding2->setUser($this->getReference('user_test'));
        $wedding2->setDate(new DateTime('2021-06-13'));
        $wedding2->setTime(new DateTime('12:00:00'));
        $wedding2->setHasJackpot(true);

        // Mourning
        $mourning = new Event();
        $mourning->setTitle('Funeraille de George');
        $mourning->setType($this->getReference('type_3'));
        $mourning->setUser($this->getReference('user_test'));
        $mourning->setDate(new DateTime('2021-06-13'));
        $mourning->setTime(new DateTime('12:00:00'));
        $mourning->setHasJackpot(true);

        // Birth
        $birth = new Event();
        $birth->setTitle('Naissance de Jeanne');
        $birth->setType($this->getReference('type_2'));
        $birth->setUser($this->getReference('user_test'));
        $birth->setDate(new DateTime('2021-06-13'));
        $birth->setTime(new DateTime('12:00:00'));
        $birth->setHasJackpot(true);

        $manager->persist($wedding);
        $manager->persist($wedding2);
        $manager->persist($birth);
        $manager->persist($birthday);
        $manager->persist($mourning);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            TypeFixtures::class,
        ];
    }
}
