<?php

namespace App\Controller;

use App\Entity\Langue;
use App\Entity\ListeVocabulaire;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\SearchFiltersListesVocabulaireType;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

final class AccueilController extends AbstractController
{
    #[Route('/accueil', name: 'app_accueil')]
    public function index(EntityManagerInterface $manager, ManagerRegistry $doctrine, SerializerInterface $serializer, Request $req): Response
    {
        $form = $this->createForm(SearchFiltersListesVocabulaireType::class);
        $form->handleRequest($req);
        $listesVocabulaire = $manager->getRepository(ListeVocabulaire::class)->findAll();


        $vars = [
            'listesVocabulaire' => $listesVocabulaire, 
            'form' => $form];
        //On envoie le array vars au template en l'ajoutant aux paramètres de render
        return $this->render('accueil/index.html.twig', $vars);
    }

    #[Route('/accueil/recherche', name: 'app_recherche')]
    public function recherche(EntityManagerInterface $manager, ManagerRegistry $doctrine, SerializerInterface $serializer, Request $req): Response
    {
        $form = $this->createForm(SearchFiltersListesVocabulaireType::class);
        $form->handleRequest($req);
        
        if ($form->isSubmitted()) {
            // dd($form->getData());
            $rep = $doctrine->getRepository(ListeVocabulaire::class);
            $resultats = $rep->searchListes($form->getData());
            
            $response = $serializer->serialize ($resultats, 'json',['groups' => 'liste-detail']) ;
            return new Response ($response);
        }
        else {
            return new Response (['error' => 'not submit']);

        }


    }
}
