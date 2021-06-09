<?php

namespace App\DataFixtures;


use App\Entity\Wedding;
use App\Entity\Birthday;
use App\Entity\Mourning;
use DateTime;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;


class EventFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $weddingCreationDate = new DateTime();
        $weddingDate = new DateTime('2020-08-01');
        $weddingTime = new DateTime('10:15:00');
        $wedding = new Wedding();
        $wedding->setWifeLastname('Fathia');
        $wedding->setWifeFirstname('Soussi');
        $wedding->setHusbandFirstname('Dupont');
        $wedding->setHusbandLastname('Jean');
        $wedding->setEventName('Mariage de Fathia');
        $wedding->setEventPostalCode('69390');
        $wedding->setEventTime($weddingTime);
        $wedding->setEventDate($weddingDate);
        $wedding->setEventDescription('Le meilleur mariage of the year');
        $wedding->setEventPicture('Image 1');
        $wedding->setEventAddress('wild code school');
        $wedding->setEventCity('Lyon');
        $wedding->setEventCountry('France');
        $wedding->setEventCreatedAt($weddingCreationDate);

        $weddingCreationDate = new DateTime();
        $birthdayDate = new DateTime('2020-08-01');
        $birthdayTime = new DateTime('10:30:00');
        $birthday = new Birthday();
        $birthday->setBirthdayDate($birthdayDate);
        $birthday->setBirthdayFirstname('John');
        $birthday->setBirthdayLastname('Doe');
        $birthday->setEventName('Anniversaire de Mister John');
        $birthday->setEventPostalCode('69390');
        $birthday->setEventTime($birthdayDate);
        $birthday->setEventDate($birthdayTime);
        $birthday->setEventDescription('25ans ça se fête les amis');
        $birthday->setEventPicture('Image 2 ');
        $birthday->setEventAddress('15 rue des tontons');
        $birthday->setEventCity('Paris');
        $birthday->setEventCountry('France');
        $birthday->setEventCreatedAt($weddingCreationDate);

        $mourningCreationDate = new DateTime();
        $mourningDate = new DateTime('2020-08-01');
        $mourningTime = new DateTime('10:30:00');
        $mourning = new Mourning();
        $mourning->setDeadBiography('Une personne en Or');
        $mourning->setDeadFirstname('Francis');
        $mourning->setDeadLastname('Desangs');
        $mourning->setDeadBiography('Comme dit, une personne en Or');
        $mourning->setDeadBirthDay($mourningDate);
        $mourning->setDeadDeathDay($mourningTime);
        $mourning->setEventName('Deces dun ange');
        $mourning->setEventPostalCode('69390');
        $mourning->setEventTime(new DateTime());
        $mourning->setEventDate(new DateTime());
        $mourning->setEventDescription('Triste nouvelle, dèces de notre cher ami Francis');
        $mourning->setEventPicture('Image 3 ');
        $mourning->setEventAddress('12 rue des anges');
        $mourning->setEventCity('Lyon');
        $mourning->setEventCountry('France');
        $mourning->setEventCreatedAt($mourningCreationDate);
        
        $manager->persist($wedding);
        $manager->persist($birthday);
        $manager->persist($mourning);

        $manager->flush();
    }
}