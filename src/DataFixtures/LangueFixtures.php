<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Langue;
use Faker;

class LangueFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i=0; $i<10; $i++){
            $langue = new Langue();
            $langue->setNom($faker->countryCode());
            $langue->setMajImportante($faker->boolean(50));

            $arrayCaracSpeciaux = [];
            for ($i2=0; $i2<$faker->numberBetween(0, 7); $i2++){
                $arrayCaracSpeciaux[] = $faker->randomLetter();
            }
            $langue->setCaracteresSpeciaux($arrayCaracSpeciaux);
            $this->addReference('langue'. $i, $langue);
            $manager->persist($langue);
        }

        $manager->flush();
    }
}
