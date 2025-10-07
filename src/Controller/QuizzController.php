<?php

namespace App\Controller;

use App\Form\QuizzType;
use Doctrine\ORM\EntityManager;
use App\Entity\ListeVocabulaire;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class QuizzController extends AbstractController
{
    #[Route('/quizzOptions/{id_liste}', name: 'app_quizzoptions')]
    public function quizzOptions(Request $request, EntityManagerInterface $em, int $id_liste): Response
    {
        $liste = $em->getRepository(ListeVocabulaire::class)->find($id_liste);
        $formQuizz = $this->createForm(QuizzType::class);
        $formQuizz->handleRequest($request);

        if ($formQuizz->isSubmitted() && $formQuizz->isValid()) {
            $difficulte = $formQuizz->get('difficulte')->getData();
            return $this->redirectToRoute('app_quizz', [
                'id_liste' => $id_liste,
                'difficulte' => $difficulte
            ]);
        }
        $vars = ['liste' => $liste, 'formQuizz' => $formQuizz];

        return $this->render(
            'quizz/index.html.twig',
            $vars
        );
    }

    #[Route('quizz/{id_liste}', name: 'app_quizz')]
    public function quizz(Request $request, int $id_liste): Response
    {
        $difficulte= $request->query->get('difficulte');
        
        if ($difficulte == "difficile"){
            $p="Ca va être difficile";
        }
        elseif ($difficulte== "moyen"){
            $p="Ca va être moyen";
        }
        else {
            $p="Ca va être facile";
        }
        $vars = ["p"=>$p];
        return $this->render('quizz/quizz_questions.html.twig', $vars);
    }
}
