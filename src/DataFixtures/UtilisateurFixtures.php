<?php

namespace App\DataFixtures;

use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker;


class UtilisateurFixtures extends Fixture
{
    //Créer une variable hasher qui nous servira ensuite dans la création du user pour hasher le mot de passe
    //Remarque : Dans les fixtures on ne peut pas injecter comme paramètres d'autres objets/managers et trucs dans le genre (comme ici par exemple)
    //Donc il faut le créer avant, avec un variable private et une fonction __construct
    private $hasher;
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    
    
    public function load(ObjectManager $manager): void
    {
        

        for ($i = 0; $i < 5; $i++) {
            $utilisateur = new Utilisateur;
            $utilisateur->setEmail("user" . $i . "@gmail.com");
            //Pour hasher le mot de passer, pour pas qu'il soit érit en clair dans la base de données
            $utilisateur->setPassword($this->hasher->hashPassword($utilisateur, "mdp"));
            $utilisateur->setNom('nom' . $i);
            $utilisateur->setRoles(['ROLE_USER']);

            $this->addReference('user' . $i, $utilisateur);
            $manager->persist($utilisateur);
        }

        for ($i = 0; $i < 5; $i++) {
            $utilisateur = new Utilisateur;
            $utilisateur->setEmail("admin" . $i . "@gmail.com");
            //Pour hasher le mot de passer, pour pas qu'il soit érit en clair dans la base de données
            $utilisateur->setPassword($this->hasher->hashPassword($utilisateur, "mdp"));
            $utilisateur->setNom('admin' . $i);
            $utilisateur->setRoles(['ROLE_ADMIN']);

            $manager->persist($utilisateur);
        }


        $manager->flush();
    }
}
