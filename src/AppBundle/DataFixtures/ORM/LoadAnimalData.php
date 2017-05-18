<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Animal;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Faker;

class LoadAnimalData extends AbstractFixture implements OrderedFixtureInterface
{

    const FAKER_LIMIT = 50;

    public function load(ObjectManager $manager)
    {
        $result = [];

        $result[] = $this->createFixture('Silvestre', 15, new \DateTime('today'), null, 'noir et blanc et rouge', 'grosminet');
        $result[] = $this->createFixture('Mais dors', 15, new \DateTime('today'), null, 'bringé', 'chien');
        $result[] = $this->createFixture('titi', 0.2, new \DateTime('today'), null, 'jaune', 'piaf');
        $result[] = $this->createFixture('5dez5f8r4ze5', 500, new \DateTime('today'), null, '#FFFFFF', 'serpent');
        $result[] = $this->createFixture('bouboule', 2, new \DateTime('today'), null, 'blanc', 'cochon-dinde');
        $result[] = $this->createFixture('bill', 8, new \DateTime('today'), null, 'roux', 'chien');

        $faker = Faker\Factory::create();

        $speciesPossibilities = LoadSpeciesData::getReferences();
        $speciesNumberMax = count($speciesPossibilities) - 1;

        for ($i = 0; $i < self::FAKER_LIMIT; ++ $i) {
            $reference = 'fake-' . $i;
            $weight = $faker->randomDigit;
            $birthdate = $faker->dateTime;
            $price = $faker->optional()->numberBetween(1, 2000);
            $color = $faker->colorName;
            $species = $speciesPossibilities[$faker->numberBetween(0, $speciesNumberMax)];

            $result[] = $this->createFixture($reference, $weight, $birthdate, $price, $color, $species);
        }

        $this->saveAll($manager, $result);
    }

    protected function saveAll(ObjectManager $manager, array $fixtures) {
        foreach ($fixtures as $value) {
            $manager->persist($value);
        }

        $manager->flush();
    }

    protected function createFixture($reference, $weight, $birthdate, $price, $color, $species) {
        $result = new Animal();

        if ($price == null) {
            $price = 0;
        }

        $result->setReference($reference);
        $result->setWeight($weight);
        $result->setBirthdate($birthdate);
        $result->setPrice($price);
        $result->setColor($color);

        $result->setSpecies($this->getReference(LoadSpeciesData::PREFIX . $species));

        return $result;
    }

    public function getOrder()
    {
        return 2000;
    }
}
