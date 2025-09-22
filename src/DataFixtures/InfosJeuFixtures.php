<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\InfosJeu;
use Faker;
use App\Entity\ListeVocabulaire;
Use App\Entity\Utilisateur;
use App\DataFixtures\ListeVocabulaireFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class InfosJeuFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager): void
    {   //Pour générer aléatoirement des données avec faker
        $faker = Faker\Factory::create('fr_FR');


        for ($i = 0; $i < 10; $i++) {
            $infosJeu = new InfosJeu();
            $infosJeu->setDateDernierJeu($faker->dateTimeBetween('-3 year', 'now'));
            $infosJeu->setBestScores([($faker->numberBetween(0, 100)), ($faker->numberBetween(0, 100)), ($faker->numberBetween(0, 100))]);

            //On ajoute la liste de vocabulaire, en récupérant les références crées dans l'autre dossier
            $infosJeu->setListeVocabulaire($this->getReference("listeVocabulaire" . rand(0, 9), ListeVocabulaire::class));
            $infosJeu->setUtilisateur($this->getReference('user'.(rand(0,4)), Utilisateur::class));
            
            $manager->persist($infosJeu);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ListeVocabulaireFixtures::class,
            UtilisateurFixtures::class,
        ];
    }
}
