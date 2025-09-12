<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Langue;
use Doctrine\ORM\EntityManagerInterface;

final class ExempleController extends AbstractController
{
    #[Route('/exemple', name: 'app_exemple')]
    public function index(): Response
    {
        return $this->render('exemple/index.html.twig', [
            'controller_name' => 'ExempleController',
        ]);
    }

    #[Route('/exemple/bonjour')]
    public function bonjour()
    {
        return $this->render('exemple/bonjour.html.twig');
    }

    #[Route('/exemple/insert')]
    //Entity Manager Interface permet d'obtenir directement manager sans passer par doctrine
    public function insertLangue(EntityManagerInterface $manager){
        $espagnol=new Langue();
        $espagnol->setNom("espagnol");
        $espagnol->setMajImportante(false);
        $espagnol->setCaracteresSpeciaux(["á", "é", "í", "ó", "ú", "ü", "ñ"]);
        $manager->persist($espagnol);
        $manager->flush();
        return $this->render('exemple/insert.html.twig');
    }
}
