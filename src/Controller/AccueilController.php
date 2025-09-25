<?php

namespace App\Controller;

use App\Entity\Langue;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\ListeVocabulaire;
use App\Form\SearchFiltersListesVocabulaireType;
use Symfony\Component\HttpFoundation\Request;


final class AccueilController extends AbstractController
{
    #[Route('/accueil', name: 'app_accueil')]
    public function index(EntityManagerInterface $manager, Request $req): Response
    {
        $form = $this->createForm(SearchFiltersListesVocabulaireType::class);
        $form->handleRequest($req);
        $listesVocabulaire = $manager->getRepository(ListeVocabulaire::class)->findAll();
        
        $vars = ['listesVocabulaire' => $listesVocabulaire, 'form' =>$form];
        //On envoie le array vars au template en l'ajoutant aux paramètres de render
        return $this->render('accueil/index.html.twig', $vars);
    }
}
