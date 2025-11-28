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

        $mot1FR = 'je';
        $mot1DE = 'ich';
        $mot2FR = 'tu';
        $mot2DE = 'du';
        $mot3FR = 'il';
        $mot3DE = 'er';
        $mot4FR = 'elle';
        $mot4DE = 'sie';


        $traductionFRDE = new Traduction();
        $traductionFRDE->setMotLangue1($mot1FR);
        $traductionFRDE->setMotLangue2($mot1DE);
        $traductionFRDE->setListeVocabulaire($this->getReference("listeFRDE", ListeVocabulaire::class));
        
        $traductionFRDE2 = new Traduction();
        $traductionFRDE2->setMotLangue1($mot2FR);
        $traductionFRDE2->setMotLangue2($mot2DE);
        $traductionFRDE2->setListeVocabulaire($this->getReference("listeFRDE", ListeVocabulaire::class));
        
        $traductionFRDE3 = new Traduction();
        $traductionFRDE3->setMotLangue1($mot3FR);
        $traductionFRDE3->setMotLangue2($mot3DE);
        $traductionFRDE3->setListeVocabulaire($this->getReference("listeFRDE", ListeVocabulaire::class));
        
        $traductionFRDE4 = new Traduction();
        $traductionFRDE4->setMotLangue1($mot4FR);
        $traductionFRDE4->setMotLangue2($mot4DE);
        $traductionFRDE4->setListeVocabulaire($this->getReference("listeFRDE", ListeVocabulaire::class));
        
        $manager->persist($traductionFRDE);
        $manager->persist($traductionFRDE2);
        $manager->persist($traductionFRDE3);
        $manager->persist($traductionFRDE4);


        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ListeVocabulaireFixtures::class,
        ];
    }
}
