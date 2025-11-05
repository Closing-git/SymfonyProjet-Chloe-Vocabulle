<?php

namespace App\Controller;

use App\Form\QuizzType;
use App\Form\ReponseType;
use App\Form\ReponseMoyenType;
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
            $langueCible = $request->request->get('langue_cible');
            return $this->redirectToRoute('app_quizz', [
                'id_liste' => $id_liste,
                'difficulte' => $difficulte,
                'langue_cible' => $langueCible,
            ]);
        }
        $vars = ['liste' => $liste, 'formQuizz' => $formQuizz];

        return $this->render(
            'quizz/index.html.twig',
            $vars
        );
    }

    #[Route('quizz/{id_liste}', name: 'app_quizz')]
    public function quizz(Request $request, int $id_liste, EntityManagerInterface $em): Response
    {
        $liste = $em->getRepository(ListeVocabulaire::class)->find($id_liste);
        $langueCible = $request->query->get('langue_cible');
        $difficulte = $request->query->get('difficulte');
        //Récupérer les mots et les mélanger
        $traductions = $liste->getTraduction()->toArray();
        shuffle($traductions);


        if ($liste->getLangues()[1]->getNom() == $langueCible) {
            $majStatut = $liste->getLangues()[1]->isMajImportante();
        } else {
            $majStatut = $liste->getLangues()[0]->isMajImportante();
        }

        //Créer la session et l'initialiser
        $session = $request->getSession();
        $questions = $session->get('questions', []);
        $i_question = $session->get('current_question', 0);
        $score = $session->get('score', 0);
        if ($questions == []) {
            $questions = $traductions;
            $session->set('questions', $questions);
            $session->set('current_question', 0);
            $session->set('score', 0);
        }

        $currentQuestion = $questions[$i_question] ?? null;


        //Quizz en fonction de chaque difficulté :
        //DIFFICILE
        if ($difficulte == "difficile") {
            $p_difficulte = "Difficile";

            $Reponseform = $this->createForm(ReponseType::class);
            $Reponseform->handleRequest($request);

            //Quand le quizz est fini (toutes les questions répondues)
            if ($i_question >= count($questions)) {
                //Supprimer les données de la session, mais stocker le score final
                $score_final = $session->get('score');
                $session->remove('questions');
                $session->remove('current_question');
                $session->remove('score');

                return $this->render('quizz/quizz_resultat.html.twig', [
                    'score_final' => $score_final,
                    'liste' => $liste,
                    'p_difficulte' => $p_difficulte,
                ]);
            }

            //En fonction de la langue cible, afficher le bon mot à traduire et récupérer la bonne réponse
            if ($liste->getLangues()[1]->getNom() == $langueCible) {
                $a_traduire = $currentQuestion->getMotLangue2();
                $bonneReponse = $currentQuestion->getMotLangue1();
            } else {
                $a_traduire = $currentQuestion->getMotLangue1();
                $bonneReponse = $currentQuestion->getMotLangue2();
            }


            if ($Reponseform->isSubmitted() && $Reponseform->isValid()) {
                $reponse = $Reponseform->get('reponse')->getData();

                //Si la maj est importante, tous les caractères doivent être respectés
                if ($majStatut == true) {
                    if ($reponse == $bonneReponse) {
                        $score++;
                        $session->set('score', $score);
                    }
                }
                //Sinon on met tout en minuscule
                else {
                    if (strtolower($reponse) == strtolower($bonneReponse)) {
                        $score++;
                        $session->set('score', $score);
                    }
                }
                $i_question++;
                $session->set('current_question', $i_question);

                return $this->redirectToRoute('app_quizz', [
                    'id_liste' => $id_liste,
                    'langue_cible' => $langueCible,
                    'difficulte' => $difficulte,
                ]);
            }


            return $this->render('quizz/quizz_questions.html.twig', [
                'Reponseform' => $Reponseform->createView(),
                'question' => $currentQuestion,
                'i_question' => $i_question,
                'score' => $score,
                'a_traduire' => $a_traduire,
                'p_difficulte' => $p_difficulte,
                'liste' => $liste,
                'langue_cible' => $langueCible,
                'majStatut' => $majStatut,
            ]);

            //MOYEN
        } elseif ($difficulte == "moyen") {
            $p_difficulte = "Moyen";


            //Quand le quizz est fini (toutes les questions répondues)
            if ($i_question >= count($questions)) {
                //Supprimer les données de la session, mais stocker le score final
                $score_final = $session->get('score');
                $session->remove('questions');
                $session->remove('current_question');
                $session->remove('score');

                return $this->render('quizz/quizz_resultat.html.twig', [
                    'score_final' => $score_final,
                    'liste' => $liste,
                    'p_difficulte' => $p_difficulte,
                ]);
            }
            //En fonction de la langue cible, afficher le bon mot à traduire, récupérer la première lettre de la bonne réponse et la bonne réponse
            if ($liste->getLangues()[1]->getNom() == $langueCible) {
                $a_traduire = $currentQuestion->getMotLangue2();
                $bonneReponse = $currentQuestion->getMotLangue1();
            } else {
                $a_traduire = $currentQuestion->getMotLangue1();
                $bonneReponse = $currentQuestion->getMotLangue2();
            }

            $premiereLettre = $bonneReponse[0];
            //Création du form avec définition des options (première lettre en attribut)
            $Reponseform = $this->createForm(ReponseMoyenType::class, null, ['premiere_lettre' => $premiereLettre]);
            $Reponseform->handleRequest($request);


            if ($Reponseform->isSubmitted() && $Reponseform->isValid()) {
                $reponse = $Reponseform->get('reponse')->getData();



                //Si la maj est importante, tous les caractères doivent être respectés
                if ($majStatut == true) {
                    if ($reponse == $bonneReponse) {
                        $score++;
                        $session->set('score', $score);
                    }
                }
                //Sinon on met tout en minuscule
                else {
                    if (strtolower($reponse) == strtolower($bonneReponse)) {
                        $score++;
                        $session->set('score', $score);
                    }
                }
                $i_question++;
                $session->set('current_question', $i_question);

                return $this->redirectToRoute('app_quizz', [
                    'id_liste' => $id_liste,
                    'langue_cible' => $langueCible,
                    'difficulte' => $difficulte,
                ]);
            }


            return $this->render('quizz/quizz_questions.html.twig', [
                'Reponseform' => $Reponseform->createView(),
                'question' => $currentQuestion,
                'i_question' => $i_question,
                'score' => $score,
                'a_traduire' => $a_traduire,
                'p_difficulte' => $p_difficulte,
                'liste' => $liste,
                'langue_cible' => $langueCible,
                'majStatut' => $majStatut,
            ]);
        } else {
            $Reponseform = $this->createForm(ReponseType::class);
            $Reponseform->handleRequest($request);
            $p_difficulte = "Facile";
        }


        $vars = ["Reponseform" => $Reponseform, "score" => $score, "p_difficulte" => $p_difficulte, "liste" => $liste, "langue_cible" => $langueCible, "majStatut" => $majStatut, "i_question" => $i_question];
        return $this->render('quizz/quizz_questions.html.twig', $vars);
    }
}
