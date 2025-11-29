<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Traduction;
use Faker;
use App\Entity\ListeVocabulaire;
use App\DataFixtures\ListeVocabulaireFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class TraductionFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {

        //FAKE LISTES
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 0; $i < 100; $i++) {
            $traduction = new Traduction();
            $traduction->setMotLangue1($faker->word());
            $traduction->setMotLangue2($faker->word());
            $traduction->setListeVocabulaire($this->getReference("listeVocabulaire" . rand(0, 9), ListeVocabulaire::class));

            $manager->persist($traduction);
        }


        //LISTES FRDE
        $motsFR = [
            "je",
            "tu",
            "il",
            "elle",
        ];
        $motsDE = [
            "ich",
            "du",
            "er",
            "sie",
        ];

        foreach ($motsFR as $key => $value) {
            $traductionFRDE = new Traduction();
            $traductionFRDE->setMotLangue1($value);
            $traductionFRDE->setMotLangue2($motsDE[$key]);
            $traductionFRDE->setListeVocabulaire($this->getReference("listeFRDE", ListeVocabulaire::class));

            $manager->persist($traductionFRDE);
        }

        //LISTES ENGFR

        $arrayFR = [
            "dire",
            "raconter",
            'demander',
            'parler',
            'crier',
            'admettre',
            'se vanter',
            'chuchoter',
        ];
        $arrayENG = [
            "to say",
            "to tell",
            "to ask",
            'to talk',
            'to shout',
            'to admit',
            'to boast',
            'to whisper'
        ];


        foreach ($arrayFR as $key => $value) {
            $traductionENGFR = new Traduction();
            $traductionENGFR->setMotLangue1($value);
            $traductionENGFR->setMotLangue2($arrayENG[$key]);
            $traductionENGFR->setListeVocabulaire($this->getReference("listeENGFR", ListeVocabulaire::class));

            $manager->persist($traductionENGFR);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ListeVocabulaireFixtures::class,
        ];
    }
}
