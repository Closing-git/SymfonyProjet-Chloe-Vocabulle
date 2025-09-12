<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\ListeVocabulaire;
use Faker;

class ListeVocabulaireFixtures extends Fixture 
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++){
            $liste = new ListeVocabulaire();
            $liste->setTitre($faker->word(5));
            $liste->setMotsLangue1([($faker->word(5)), ($faker->word(10)), ($faker->word(2)), ($faker->word(5)), ($faker->word(15))]);
            $liste->setMotsLangue2([($faker->word(5)), ($faker->word(10)), ($faker->word(5)), ($faker->word(8)), ($faker->word(9))]);
            $liste->setNbMots(count($liste->getMotsLangue1()));
            $liste->setDateDerniereModif($faker->dateTimeBetween('-1 year', 'now'));
            $liste->setPublicStatut($faker->boolean(50));

            //Ca créé une référence dans la mémoire, partagée par toutes les fixtures
            $this->addReference('listeVocabulaire'. $i, $liste);
            $manager->persist($liste);
        }

        $manager->flush();
    }
}
