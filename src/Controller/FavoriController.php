<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Entity\ListeVocabulaire;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;

final class FavoriController extends AbstractController
{
    #[Route('/favori/{id}', name: 'app_favori', methods: ['POST'])]
    public function toggleFavori(int $id, ListeVocabulaire $liste, EntityManagerInterface $em): JsonResponse
    {

        dd($liste);
        $user = $this->getUser();
        // $liste = $em->getRepository(ListeVocabulaire::class)->find($id);

        if ($liste->getUtilisateursQuiFav()->contains($user)) {
            $liste->removeUtilisateursQuiFav($user);
            $isFavori = false;
        } else {
            $liste->addUtilisateursQuiFav($user);
            $isFavori = true;
        }
        $em->persist($liste);
        $em->persist($user);
        $em->flush();

        return $this->json([
            'isFavori' => $isFavori
        ]);
    }
}
