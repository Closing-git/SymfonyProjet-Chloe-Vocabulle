<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Note;
use App\Entity\Utilisateur;
use Faker;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class NoteFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {   //Pour générer aléatoirement des données avec faker
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $note = new Note();
            $note->setMontantNote($faker->numberBetween(0, 5));
            $note->setUtilisateur($this->getReference("user" . (rand(0, 4)), Utilisateur::class));
            $manager->persist($note);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UtilisateurFixtures::class,
        ];
    }
}
