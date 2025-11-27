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

        for ($i = 0; $i < 10; $i++) {
            $langue = new Langue();
            $langue->setNom($faker->countryCode());
            $langue->setMajImportante($faker->boolean(50));

            $arrayCaracSpeciaux = [];
            for ($i2 = 0; $i2 < $faker->numberBetween(0, 7); $i2++) {
                $arrayCaracSpeciaux[] = $faker->randomLetter();
            }
            $langue->setCaracteresSpeciaux($arrayCaracSpeciaux);
            $this->addReference('langue' . $i, $langue);
            $manager->persist($langue);
        }

        // FR - Français
        $FR = new Langue();
        $FR->setNom("FR");
        $FR->setMajImportante(false);

        $arrayCaracSpeciaux = ['â', 'à', 'ç', 'é', 'è', 'ê', 'ë', 'î', 'ï', 'ô', 'œ', 'ù', 'û'];
        $FR->setCaracteresSpeciaux($arrayCaracSpeciaux);
        $this->addReference('FR', $FR);
        $manager->persist($FR);

        //ENG - Anglais

        $ENG = new Langue();
        $ENG->setNom("ENG");
        $ENG->setMajImportante(false);

        $arrayCaracSpeciaux = [];
        $ENG->setCaracteresSpeciaux($arrayCaracSpeciaux);
        $this->addReference('ENG', $ENG);
        $manager->persist($ENG);

        //DE - Allemand

        $DE = new Langue();
        $DE->setNom("DE");
        $DE->setMajImportante(true);

        $arrayCaracSpeciaux = ['ä', 'Ä', 'ö', 'Ö', 'ü', 'Ü', 'ß'];
        $DE->setCaracteresSpeciaux($arrayCaracSpeciaux);
        $this->addReference('DE', $DE);
        $manager->persist($DE);

        //ES - ESPAGNOL

        $ES = new Langue();
        $ES->setNom("ES");
        $ES->setMajImportante(false);

        $arrayCaracSpeciaux = ['á', 'é', 'í', 'ñ', 'ó', 'ú', 'ü'];
        $ES->setCaracteresSpeciaux($arrayCaracSpeciaux);
        $this->addReference('ES', $ES);
        $manager->persist($ES);



        $manager->flush();
    }
}
