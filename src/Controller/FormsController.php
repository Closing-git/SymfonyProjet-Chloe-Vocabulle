<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\LangueType;
use App\Entity\Langue;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

final class FormsController extends AbstractController
{
    #[Route('/afficher/form', name: 'app_afficher_forms')]
    public function afficherform(): Response
    {
        $formLangue = $this->createForm(LangueType::class);
        $vars = ['formLangue' => $formLangue];

        return $this->render('forms/afficher_form.html.twig', $vars);
    }


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
