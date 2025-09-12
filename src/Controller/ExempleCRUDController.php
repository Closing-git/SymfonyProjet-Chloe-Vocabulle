<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\InfosJeu;
use App\Entity\ListeVocabulaire;
use App\Entity\Langue;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;


class ExempleCRUDController extends AbstractController {
    #[Route("/infosJeu/insert")]
    public function insererInfosJeu(ManagerRegistry $doctrine){
        //Créer un nouvel objet InfosJeu
        $p1=new InfosJeu();
        $p1->setDateDernierJeu(new \DateTime());
        $p1->setBestScoresDifficultes([50,2,100]);

        $liste1=new ListeVocabulaire();
        $liste1->setTitre("Liste 1");
        $liste1->setNbMots(2);
        $liste1->setMotsLangue1(["mot1FR","mot2FR"]);
        $liste1->setMotsLangue2(["mot1ENG","mot2ENG"]);
        $liste1->setDateDerniereModif(new \DateTime());
        $liste1->setPublicStatut(true);
        $liste1->setNoteTotale(100);

        $anglais=new Langue();
        $anglais->setNom("anglais");
        $anglais->setMajImportante(false);
        $anglais->setCaracteresSpeciaux([]);
        $français=new Langue();
        $français->setNom("français");
        $français->setMajImportante(false);
        $français->setCaracteresSpeciaux(["é", "è", "ê", "ë", "à", "â", "ä", "ù", "û", "ü", "ç"]);

        $liste1->addLangue($anglais);
        $liste1->addLangue($français);


        $p1->setListeVocabulaire($liste1);


        //Insérer dans la base de données
        //On créé un objet manager qui nous permet ensuite d'insérer p1 dans la db
        $manager = $doctrine->getManager();
        $manager->persist($p1);
        $manager->persist($liste1);
        $manager->persist($anglais);
        $manager->persist($français);
        $manager->flush();

        
        return new Response("InfosJeu inserer");
    }
}