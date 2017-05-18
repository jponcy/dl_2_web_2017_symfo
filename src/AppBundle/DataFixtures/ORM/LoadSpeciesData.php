<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Species;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class LoadSpeciesData extends AbstractFixture implements OrderedFixtureInterface
{

    const PREFIX = 'species-';

    private static $references = [];

    public function load(ObjectManager $manager)
    {
        $result = [];

        $result[] = $this->createFixture('serpent', 500);
        $result[] = $this->createFixture('grosminet', 500000);
        $result[] = $this->createFixture('chien', 500);
        $result[] = $this->createFixture('Piaf', 5);
        $result[] = $this->createFixture('petitminet', 0);
        $result[] = $this->createFixture('cochon-dinde', 15);
        $result[] = $this->createFixture('souris', 5);
        $result[] = $this->createFixture('rat', 10);

        $this->saveAll($manager, $result);
    }

    protected function saveAll(ObjectManager $manager, array $fixtures) {
        foreach ($fixtures as $value) {
            $manager->persist($value);
        }

        $manager->flush();
    }

    protected function createFixture(string $name, float $price) {
        $result = new Species();

        $result->setName($name);
        $result->setPrice($price);

        $reference = self::PREFIX . strtolower($name);
        $this->addReference($reference, $result);
        self::$references[] = strtolower($name);

        return $result;
    }

    public function getOrder()
    {
        return 2000;
    }

    public static function getReferences() {
        return self::$references;
    }
}
