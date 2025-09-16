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
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 0; $i < 100; $i++) {
            $traduction = new Traduction();
            $traduction->setMotLangue1($faker->word());
            $traduction->setMotLangue2($faker->word());
            $traduction->setListeVocabulaire($this->getReference("listeVocabulaire" . rand(0, 9), ListeVocabulaire::class));

            $manager->persist($traduction);
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
