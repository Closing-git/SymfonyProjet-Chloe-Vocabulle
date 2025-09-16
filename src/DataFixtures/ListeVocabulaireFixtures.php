<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\ListeVocabulaire;
use App\Entity\Langue;
use Faker;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class ListeVocabulaireFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $liste = new ListeVocabulaire();
            $liste->setTitre($faker->word(5));
            $liste->setDateDerniereModif($faker->dateTimeBetween('-1 year', 'now'));
            $liste->setPublicStatut($faker->boolean(50));



            //Ca créé une référence dans la mémoire, partagée par toutes les fixtures
            $this->addReference('listeVocabulaire' . $i, $liste);
            //On récupère la référence de langue (2 fois) et on l'ajoute à la liste
            //Boucle while pour éviter d'avoir deux fois la même langue
            while ($liste->getLangues()->count() !=2) {
                $liste->addLangue($this->getReference("langue" . rand(0, 9), Langue::class));
            }
            $manager->persist($liste);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            LangueFixtures::class,

        ];
    }
}
