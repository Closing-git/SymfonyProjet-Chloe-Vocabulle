<?php

namespace App\Controller;

use App\Form\QuizzType;
use Doctrine\ORM\EntityManager;
use App\Entity\ListeVocabulaire;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class QuizzController extends AbstractController
{
    #[Route('/quizzOptions/{id_liste}', name: 'app_quizzoptions')]
    public function index(EntityManagerInterface $em, int $id_liste): Response
    {
        $liste = $em->getRepository(ListeVocabulaire::class)->find($id_liste);
        $formQuizz = $this->createForm(QuizzType::class);
        $vars = ['liste' => $liste, 'formQuizz'=>$formQuizz];

        return $this->render('quizz/index.html.twig',$vars
        );
    }
}
