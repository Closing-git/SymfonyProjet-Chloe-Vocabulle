<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Note;
use Faker;

class NoteFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {   //Pour générer aléatoirement des données avec faker
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++){
            $note = new Note();
            $note->setMontantNote($faker->numberBetween(0, 5));
            $manager->persist($note);
        }

        $manager->flush();
    }
}
