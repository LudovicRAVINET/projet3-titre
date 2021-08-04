<?php

namespace App\DataFixtures;

use App\Entity\Type;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * @codeCoverageIgnore
 */
class TypeFixtures extends Fixture
{
    public const EVENT_TYPE = ['wedding',
                        'birthday',
                        'birth',
                        'mourning',
                        'others'];

    public function load(ObjectManager $manager)
    {
        foreach (self::EVENT_TYPE as $key => $eventType) {
            $type = new Type();
            $type->setName($eventType);

            if ($eventType === "wedding") {
                $type->setDefaultPicture(
                    'defaultWedding.png'
                );
            }
            if ($eventType === "birth") {
                $type->setDefaultPicture(
                    'defaultBirth.png'
                );
            }
            if ($eventType === "birthday") {
                $type->setDefaultPicture(
                    'defaultBirthday.png'
                );
            }
            if ($eventType === "mourning") {
                $type->setDefaultPicture(
                    'defaultMourning.png'
                );
            }
            if ($eventType === "others") {
                $type->setDefaultPicture(
                    'defaultOthers.png'
                );
            }
            $this->addReference('type_' . $key, $type);
            $manager->persist($type);
        }
        $manager->flush();
    }
}
