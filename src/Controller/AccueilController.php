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
        //Reset des infos de la session (si on vient d'un quizz non fini)
        $session = $req->getSession();
        $session->remove('questions');
        $session->remove('current_question');
        $session->remove('score');
        $session->remove('scoreEnPourcentage');
        $session->remove('erreurs');
        $session->remove('bonnesReponses');
        $session->remove('questionsApresErreur');
        $session->remove('a_traduireApresErreur');
        
        $form = $this->createForm(SearchFiltersListesVocabulaireType::class);
        $form->handleRequest($req);
        $listesVocabulaire = $manager->getRepository(ListeVocabulaire::class)->findAll();


        $vars = [
            'listesVocabulaire' => $listesVocabulaire,
            'form' => $form
        ];
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
            $resultats = $rep->searchListes($form->getData(), $this->getUser());

            $response = $serializer->serialize($resultats, 'json', ['groups' => 'liste-detail']);
            return new Response($response);
        } else {
            return new Response(['error' => 'not submit']);
        }
    }

    #[Route('/liste/{id}/fav-toggle', name: 'app_fav_toggle', methods: ['POST'])]
    public function toggleFav(ListeVocabulaire $liste, Request $request, EntityManagerInterface $em): Response
    {
        //Récupère le user
        $user = $this->getUser();

        // CSRF Token : On en a besoin pour éviter qu'un tier utilise POST pour changer massivement la base de données
        $token = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('fav_toggle_' . $liste->getId(), $token)) {
            $this->addFlash('error', 'Action non autorisée.');
            return $this->redirectToRoute('app_accueil');
        }

        //On récupère tous les utilisateurs qui fav
        $favUsers = $liste->getUtilisateursQuiFav();
        //On remove ou add en fonction des cas
        if ($favUsers->contains($user)) {
            $liste->removeUtilisateursQuiFav($user);
            $this->addFlash('success', sprintf($liste->getTitre() . ' retirée de vos favoris.'));
        } else {
            $liste->addUtilisateursQuiFav($user);
            $this->addFlash('success', sprintf($liste->getTitre() . ' ajoutée à vos favoris.'));
        }

        $em->flush();
        return $this->redirectToRoute('app_accueil');
    }
}
