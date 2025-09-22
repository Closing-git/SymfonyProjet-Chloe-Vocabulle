<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\ListeVocabulaire;
use App\Entity\Note;
use Faker;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class NoteListeVocabulaireFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        //Obtenir tous les listes de vocabulaire
        //ListeVocabulaire::class est un raccourci pour App\Entity\ListeVocabulaire
        $listes = $manager->getRepository(ListeVocabulaire::class)->findAll();
        //Obtenir toutes les notes
        $notes = $manager->getRepository(Note::class)->findAll();

        //Parcourir les objets du côté n de la relation = Note 
        //et affecter un objet aléatoire du côté 1 = ListeVocabulaire

        foreach ($notes as $note){
            $note->setListeVocabulaire($listes[$faker->numberBetween(0,count($listes)-1)]);
        }
    
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            NoteFixtures::class,
            ListeVocabulaireFixtures::class,
        ];
    }
}
