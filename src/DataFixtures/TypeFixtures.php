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
        foreach (self::EVENT_TYPE as $key => $eventType) {
            $type = new Type();
            $type->setName($eventType);

            if ($eventType === "wedding") {
                $type->setDefaultPicture(
                    'https://github.com/Bachir-Ndiaye/eventoo-images/
blob/main/images/mariage/mariage-ring.png?raw=true'
                );
            }
            if ($eventType === "birth") {
                $type->setDefaultPicture(
                    'https://github.com/Bachir-Ndiaye/eventoo-images/
blob/main/images/naissance/naissance-default.png?raw=true'
                );
            }
            if ($eventType === "birthday") {
                $type->setDefaultPicture(
                    'https://github.com/Bachir-Ndiaye/eventoo-images/
blob/main/images/anniversaire/anniversaire-default.png?raw=true'
                );
            }
            if ($eventType === "mourning") {
                $type->setDefaultPicture(
                    'https://github.com/Bachir-Ndiaye/eventoo-images/
blob/main/images/deuil/deuil-default.png?raw=true'
                );
            }
            if ($eventType === "others") {
                $type->setDefaultPicture(
                    'https://github.com/Bachir-Ndiaye/eventoo-images/
blob/main/images/anniversaire/annniversaire-rose.png?raw=true'
                );
            }
            $this->addReference('type_' . $key, $type);
            $manager->persist($type);
        }
        $manager->flush();
    }
}
