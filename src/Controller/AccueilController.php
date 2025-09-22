<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\ListeVocabulaire;


final class AccueilController extends AbstractController
{
    #[Route('/accueil', name: 'app_accueil')]
    public function index(): Response
    {
        $produit = ['nom'=>"pistache", "prix"=>12.5];
        //On stocke les données dont on a besoin dans un array vars (remarque on peut avoir un array dans un array et on y accède dans le template
        //avec produit.nom par exemple)
        $vars = ['nom'=>'Chloe', 'dateNaissance'=>new \DateTime('2005-09-15'), 'produit'=>$produit];
        //On envoie le array vars au template en l'ajoutant aux paramètres de render
        return $this->render('accueil/index.html.twig', $vars);
    }

    #[Route('/accueil/testModele', name:'app_testModele')]
    public function testModele(EntityManagerInterface $manager) {
        //Récupére le repository de Liste dans rep
        $rep=$manager->getRepository(ListeVocabulaire::class);
        //Récupère toutes les listes et stocke les dans $listesVocabulaire, ça renvoie une collection
        $listesVocabulaire=$rep->findAll();

        $vars=['listesVocabulaire'=>$listesVocabulaire];



        return $this->render('accueil/testModele.html.twig', $vars);
    }
}
