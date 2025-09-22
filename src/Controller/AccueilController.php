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
    public function index(EntityManagerInterface $manager): Response
    {   $rep=$manager->getRepository(ListeVocabulaire::class);
        $listesVocabulaire=$rep->findAll();

        $vars = ['listesVocabulaire'=>$listesVocabulaire];
        //On envoie le array vars au template en l'ajoutant aux paramètres de render
        return $this->render('accueil/index.html.twig', $vars);
    }
}
