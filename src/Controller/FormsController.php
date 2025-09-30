<?php

namespace App\Controller;

use App\Entity\Langue;
use App\Form\ListeType;
use App\Form\LangueType;
use App\Entity\ListeVocabulaire;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class FormsController extends AbstractController
{
    #[Route('modifier/liste/{id_liste}', name: 'app_modifier_liste')]
    public function modifierListe(EntityManagerInterface $em, int $id_liste, Request $request): Response
    {
        $liste = new ListeVocabulaire();

        $liste = $em->getRepository(ListeVocabulaire::class)->find($id_liste);
        //Comme langue 1 et langue 2 ne sont pas mappées on doit les récupérer avant de créer le formulaire
        $langues = $liste->getLangues(); // Donne une collection
        $langue1 = $langues->get(0);
        $langue2 = $langues->get(1);

        $formListe = $this->createForm(ListeType::class, $liste);
        $formListe->get('langue1')->setData($langue1);
        $formListe->get('langue2')->setData($langue2);

        $vars = ['formListe' => $formListe];


        //UPDATE
        $formListe->handleRequest($request);
        // Récupérer les sélections des champs non mappés
        $selected1 = $formListe->get('langue1')->getData();
        $selected2 = $formListe->get('langue2')->getData();

        //S'assurer que la langue 1 et la langue 2 ne sont pas les mêmes
        //Ajouter un message d'erreur que l'on peut récupérer dans le template et permet de rendre isValid = False
        if ($selected1 && $selected2 && $selected1->getId() === $selected2->getId()) {
            $formListe->get('langue2')->addError(
                new FormError('Les deux langues ne peuvent pas être les mêmes.')
            );
        }
        if ($formListe->isSubmitted() && $formListe->isValid()) {
            // Vider les langues, avant de les réajouter
            $liste->getLangues()->clear();
            if ($selected1) {
                $liste->addLangue($selected1);
            }
            if ($selected2) {
                $liste->addLangue($selected2);
            }

            $em->flush();

            $this->addFlash('success', 'La liste de vocabulaire a été mise à jour.');
            // Redirection après succès 
            return $this->redirectToRoute('app_modifier_liste', ['id_liste' => $liste->getId()]);
        }
        return $this->render('forms/modifier_liste.html.twig', $vars);
    }



    //DELETE
    #[Route ("/supprimer/liste/{id_liste}", name: 'app_supprimer_liste')]
    public function listeDelete(ManagerRegistry $doctrine, int $id_liste)
    {
        $listeToDelete = new ListeVocabulaire();
        $em = $doctrine->getManager();
        $listeToDelete = $em->getRepository(ListeVocabulaire::class)->find($id_liste);
        $em->remove($listeToDelete);
        $em->flush();
        return $this->redirectToRoute("app_accueil");
    }




    //EXEMPLES : EXERCICES
    #[Route('form/insert/langue', name: 'app_forms_insert_langue')]
    public function insertLangue(Request $req, EntityManagerInterface $em): Response
    {
        $langue = new Langue();

        $langue = $em->getRepository(Langue::class)->find(1);

        $formLangue = $this->createForm(LangueType::class, $langue);


        //Req est la requête envoyée (Post ou Get) // HandleRequest rempli les propriétes de langue à partir des données du formulaire
        $formLangue->handleRequest($req);

        //2 situations :
        //1 : le formulaire est rempli (valide) et envoyé
        if ($formLangue->isSubmitted() && $formLangue->isValid()) {
            $em->persist($langue);
            $em->flush();
            //Renvoie vers la page qui affiche les langues (bien mettre la route et pas le html)
            return $this->redirectToRoute('app_forms_afficher_langues');
        }

        //2 : le formulaire n'est pas soumis
        else {

            $vars = ['formLangue' => $formLangue];
            return $this->render('forms/affiche_form_insert_animal.html.twig', $vars);
        }
    }


    #[Route('/afficher/langues', name: 'app_forms_afficher_langues')]
    public function afficherLangues(EntityManagerInterface $em)
    {
        $langues = $em->getRepository(Langue::class)->findAll();
        $vars = ['langues' => $langues];

        return $this->render('forms/affiche_resultat_traitement_form_insert.html.twig', $vars);
    }
}
