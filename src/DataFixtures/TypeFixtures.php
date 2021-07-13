<?php

namespace App\DataFixtures;

use App\Entity\Type;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TypeFixtures extends Fixture
{
    public const EVENT_TYPE = ['wedding',
                        'birthday',
                        'birth',
                        'mourning',
                        'others'];

    public function load(ObjectManager $manager)
    {
        foreach (self::EVENT_TYPE as $eventType) {
            $type = new Type();
            $type->setName($eventType);
            $type->setDefaultPicture('https://media.istockphoto.com/photos
                                    /audience-applauding-in-the-theater-picture-
                                    id1207062016?b=1&k=6&m=1207062016&s=170667a&w=
                                    0&h=MBa78DK96o5A5ImO9G8QBmdHD0hkNoXKPl0S7Vb5eZA=');
        }
    }
}
