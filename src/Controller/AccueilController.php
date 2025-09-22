<?php

namespace App\Controller;

use App\Entity\Langue;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\ListeVocabulaire;
use Symfony\Component\HttpFoundation\Request;


final class AccueilController extends AbstractController
{
    #[Route('/accueil', name: 'app_accueil')]
    public function index(EntityManagerInterface $manager, Request $req): Response {
    
    $id=$req->get('id_langue');

    if ($id == "all")
    {
            $rep=$manager->getRepository(ListeVocabulaire::class);
            $rep2=$manager->getRepository(Langue::class);
            $listesVocabulaire=$rep->findAll();
            $langues=$rep2->findAll();
    }

        else {
            $rep=$manager->getRepository(ListeVocabulaire::class);
            $rep2=$manager->getRepository(Langue::class);
            $langues=$rep2->findAll();
            $langueSelected=$rep2->find($id);
            $listesVocabulaire=$langueSelected->getListesVocabulaire();

        }
        $vars = ['listesVocabulaire'=>$listesVocabulaire, 'langues'=>$langues];
        //On envoie le array vars au template en l'ajoutant aux paramètres de render
        return $this->render('accueil/index.html.twig', $vars);
    }


}


