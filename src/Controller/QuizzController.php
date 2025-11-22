<?php

namespace App\Controller;

use App\Entity\Note;
use App\Form\QuizzType;
use App\Entity\InfosJeu;
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
            $caracteres = $liste->getLangues()[1]->getCaracteresSpeciaux();
        } else {
            $majStatut = $liste->getLangues()[0]->isMajImportante();
            $caracteres = $liste->getLangues()[0]->getCaracteresSpeciaux();
        }

        //Créer la session et l'initialiser
        $session = $request->getSession();
        $questions = $session->get('questions', []);
        $i_question = $session->get('current_question', 0);
        $score = $session->get('score', 0);
        $scoreEnPourcentage = $session->get('scoreEnPourcentage', 0);

        if ($questions == []) {
            $questions = $traductions;
            $session->set('questions', $questions);
            $session->set('current_question', 0);
            $session->set('score', 0);
            $session->set('scoreEnPourcentage', 0);
            // Enregistrer la langue cible et la langue source
            if ($liste->getLangues()[1]->getNom() == $langueCible) {
                $langueSource = $liste->getLangues()[0]->getNom();
            } else {
                $langueSource = $liste->getLangues()[1]->getNom();
            }
            $session->set('langue_cible', $langueCible);
            $session->set('langue_source', $langueSource);
        }

        $currentQuestion = $questions[$i_question] ?? null;


        //Quizz en fonction de chaque difficulté :
        //DIFFICILE
        if ($difficulte == "difficile" || $difficulte == "moyen") {

            //Quand le quizz est fini (toutes les questions répondues)
            if ($i_question >= count($questions)) {
                //Supprimer les données de la session, mais stocker le score final et les erreurs + bonnes réponses
                $erreurs = $session->get('erreurs', []);
                $bonnesReponses = $session->get('bonnesReponses', []);
                $score_final = $session->get('scoreEnPourcentage');
                $questionsApresErreur = $session->get('questionsApresErreur', []);
                $a_traduireApresErreur = $session->get('a_traduireApresErreur', []);
                $langueCible = $session->get('langue_cible');
                $langueSource = $session->get('langue_source');
                //Supprimer les infos de la session
                $session->remove('erreurs');
                $session->remove('bonnesReponses');
                $session->remove('questionsApresErreur');
                $session->remove('questions');
                $session->remove('current_question');
                $session->remove('score');
                $session->remove('scoreEnPourcentage');
                $session->remove('a_traduireApresErreur');

                $infosJeu = $em->getRepository(InfosJeu::class)->findOneBy(['listeVocabulaire' => $liste, 'utilisateur' => $this->getUser()]);

                // Si aucunes infosJeu, créer un objet InfosJeu
                if (!$infosJeu) {
                    $infosJeu = new InfosJeu();
                    $infosJeu->setListeVocabulaire($liste);
                    $infosJeu->setUtilisateur($this->getUser());
                    $infosJeu->setBestScores([0, 0, 0]); // Initialiser avec des scores à 0
                    $em->persist($infosJeu);
                }
                $infosJeu->setDateDernierJeu(new \DateTime());

                //Gérer meilleurs scores
                $bestScores = $infosJeu->getBestScores();
                if ($difficulte == "difficile") {
                    $previousBestScore = $bestScores[2];
                    $p_difficulte = "Difficile";
                    if ($score_final > $previousBestScore) {
                        $bestScores[2] = $score_final;
                    }
                } else {
                    $p_difficulte = "Moyen";
                    $previousBestScore = $bestScores[1];
                    if ($score_final > $previousBestScore) {
                        $bestScores[1] = $score_final;
                    }
                }
                $note = $em->getRepository(Note::class)->findOneBy([
                    'utilisateur' => $this->getUser(),
                    'listeVocabulaire' => $liste
                ]);

                if ($note) {
                    $userNote = $note->getMontantNote();
                } else {
                    $userNote = null;
                }

                $infosJeu->setBestScores($bestScores);
                $em->flush();


                return $this->render('quizz/quizz_resultat.html.twig', [
                    'score_final' => $score_final,
                    'id_liste' => $id_liste,
                    'liste' => $liste,
                    'p_difficulte' => $p_difficulte,
                    'erreurs' => $erreurs,
                    'bonnesReponses' => $bonnesReponses,
                    'questionsApresErreur' => $questionsApresErreur,
                    'a_traduireApresErreur' => $a_traduireApresErreur,
                    'userNote' => $userNote,
                    'langueCible' => $langueCible,
                    'langueSource' => $langueSource,
                    'caracteresSpeciaux' => $caracteres
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
            if ($difficulte == "difficile") {
                $p_difficulte = "Difficile";
                $Reponseform = $this->createForm(ReponseType::class);
                $Reponseform->handleRequest($request);
            } else {
                $p_difficulte = "Moyen";
                $premiereLettre = $bonneReponse[0];
                //Création du form avec définition des options (première lettre en attribut)
                $Reponseform = $this->createForm(ReponseMoyenType::class, null, ['premiere_lettre' => $premiereLettre]);
                $Reponseform->handleRequest($request);
            }




            // Envoie de la réponse
            if ($Reponseform->isSubmitted() && $Reponseform->isValid()) {
                $reponse = $Reponseform->get('reponse')->getData();

                //ALGO POUR PRESQUE

                //S'il a déjà eu un presque, ne pas appliquer l'algo
                if ($session->get('presqueInit') == True) {
                    $presque = False;
                    $session->set('presqueInit', False);
                } else {

                    # Quel est le mot le plus long et le mot le plus court
                    if (strlen($reponse) > strlen($bonneReponse)) {
                        $shorter_word = $bonneReponse;
                        $longer_word = $reponse;
                    } else {
                        $shorter_word = $reponse;
                        $longer_word = $bonneReponse;
                    }
                    # Calculer la différence de nombre de lettre entre les deux mots
                    $diff_len = strlen($longer_word) - strlen($shorter_word);
                    $nb_placed_letters = 0;
                    # Calculer le nombre de lettres bien placées(en fonction du plus petit mot)
                    for ($i = 0; $i < strlen($shorter_word); $i++) {
                        if (strtolower($shorter_word[$i]) == strtolower($longer_word[$i])) {
                            $nb_placed_letters++;
                        }
                    }

                    #Caculer le pourcentage de lettres justes avec le nb de lettres en communs ET les lettres en trop ou pas assez
                    $percentage_placed_letters = (($nb_placed_letters - $diff_len) / (strlen($shorter_word))) * 100;


                    #Caclculer le nombre de lettres en commun
                    $nb_common_letters = 0;

                    for ($i = 0, $n = strlen($shorter_word); $i < $n; $i++) {
                        $letter = $longer_word[$i];
                        if (strpos(strtolower($shorter_word), $letter) !== false) {
                            $nb_common_letters++;
                        }
                    }

                    #Calculer le pourcentage de lettres en commun
                    $percentage_common_letters = ($nb_common_letters / strlen($shorter_word)) * 100;

                    if ($percentage_placed_letters >= 70 || $percentage_common_letters >= 80 || ($percentage_common_letters > 65 & $percentage_placed_letters > 65)) {
                        $presque = True;
                        $session->set('presqueInit', True);
                    } else {
                        $presque = False;
                    }
                }


                //Vérification bonne ou mauvaise réponse 
                //Si la maj est importante, tous les caractères doivent être respectés
                if ($majStatut == true) {
                    if ($reponse == $bonneReponse) {
                        $score++;
                        $scoreEnPourcentage = round(($score / count($questions)) * 100);
                        $session->set('score', $score);
                        $session->set('scoreEnPourcentage', $scoreEnPourcentage);
                        $corrige = 'Correct';
                    }
                    //Si PRESQUE
                    elseif ($presque == True) {
                        return $this->render('quizz/quizz_presque.html.twig', [
                            'Reponseform' => $Reponseform->createView(),
                            'question' => $currentQuestion,
                            'liste' => $liste,
                            'langue_cible' => $langueCible,
                            'difficulte' => $difficulte,
                            'i_question' => $i_question,
                            'scoreEnPourcentage' => $scoreEnPourcentage,
                            'a_traduire' => $a_traduire,
                            'p_difficulte' => $p_difficulte,
                            'majStatut' => $majStatut,
                            'id_liste' => $id_liste,
                            'caracteresSpeciaux' => $caracteres,
                            'bonneReponse' => $bonneReponse,
                            'reponse' => $reponse,
                            'a_traduire' => $a_traduire,

                        ]);
                    } else {
                        //Récupérer les erreurs déjà existantes (si unset, créer un tableau vide)
                        $erreurs = $session->get('erreurs', []);
                        $bonnesReponses = $session->get('bonnesReponses', []);
                        $questionsApresErreur = $session->get('questionsApresErreur', []);
                        $a_traduireApresErreur = $session->get('a_traduireApresErreur', []);

                        $erreurs[] = $reponse;
                        $bonnesReponses[] = $bonneReponse;
                        $questionsApresErreur[] = $currentQuestion;
                        $a_traduireApresErreur[] = $a_traduire;

                        $session->set('erreurs', $erreurs);
                        $session->set('bonnesReponses', $bonnesReponses);
                        $session->set('questionsApresErreur', $questionsApresErreur);
                        $session->set('a_traduireApresErreur', $a_traduireApresErreur);
                        $corrige = 'Incorrect';
                    }
                }
                //Sinon on met tout en minuscule et on compare
                else {
                    if (strtolower($reponse) == strtolower($bonneReponse)) {
                        $score++;
                        $scoreEnPourcentage = round(($score / count($questions)) * 100);
                        $session->set('score', $score);
                        $session->set('scoreEnPourcentage', $scoreEnPourcentage);
                        $corrige = 'Correct';
                    } elseif ($presque == True) {
                        return $this->render('quizz/quizz_presque.html.twig', [
                            'Reponseform' => $Reponseform->createView(),
                            'question' => $currentQuestion,
                            'liste' => $liste,
                            'langue_cible' => $langueCible,
                            'difficulte' => $difficulte,
                            'i_question' => $i_question,
                            'scoreEnPourcentage' => $scoreEnPourcentage,
                            'a_traduire' => $a_traduire,
                            'p_difficulte' => $p_difficulte,
                            'majStatut' => $majStatut,
                            'id_liste' => $id_liste,
                            'caracteresSpeciaux' => $caracteres,
                            'bonneReponse' => $bonneReponse,
                            'reponse' => $reponse,
                            'a_traduire' => $a_traduire,

                        ]);
                    } else {
                        //Récupérer les erreurs déjà existantes (si unset, créer un tableau vide)
                        $erreurs = $session->get('erreurs', []);
                        $bonnesReponses = $session->get('bonnesReponses', []);
                        $questionsApresErreur = $session->get('questionsApresErreur', []);
                        $a_traduireApresErreur = $session->get('a_traduireApresErreur', []);

                        $erreurs[] = $reponse;
                        $bonnesReponses[] = $bonneReponse;
                        $questionsApresErreur[] = $currentQuestion;
                        $a_traduireApresErreur[] = $a_traduire;

                        $session->set('erreurs', $erreurs);
                        $session->set('bonnesReponses', $bonnesReponses);
                        $session->set('questionsApresErreur', $questionsApresErreur);
                        $session->set('a_traduireApresErreur', $a_traduireApresErreur);
                        $corrige = 'Incorrect';
                    }
                }
                $i_question++;
                $session->set('current_question', $i_question);

                // Corrigé de chaque question
                return $this->render('quizz/quizz_corriges.html.twig', [
                    'question' => $currentQuestion,
                    'corrige' => $corrige,
                    'liste' => $liste,
                    'langue_cible' => $langueCible,
                    'difficulte' => $difficulte,
                    'i_question' => $i_question,
                    'scoreEnPourcentage' => $scoreEnPourcentage,
                    'a_traduire' => $a_traduire,
                    'p_difficulte' => $p_difficulte,
                    'majStatut' => $majStatut,
                    'id_liste' => $id_liste,
                    'caracteresSpeciaux' => $caracteres,
                    'bonneReponse' => $bonneReponse,
                    'reponse' => $reponse,
                    'a_traduire' => $a_traduire,

                ]);



                // return $this->redirectToRoute('app_quizz', [
                //     'id_liste' => $id_liste,
                //     'langue_cible' => $langueCible,
                //     'difficulte' => $difficulte,
                //     'caracteresSpeciaux' => $caracteres
                // ]);
            }


            return $this->render('quizz/quizz_questions.html.twig', [
                'Reponseform' => $Reponseform->createView(),
                'question' => $currentQuestion,
                'i_question' => $i_question,
                'scoreEnPourcentage' => $scoreEnPourcentage,
                'difficulte' => $difficulte,
                'a_traduire' => $a_traduire,
                'p_difficulte' => $p_difficulte,
                'liste' => $liste,
                'langue_cible' => $langueCible,
                'majStatut' => $majStatut,
                'id_liste' => $id_liste,
                'caracteresSpeciaux' => $caracteres,

            ]);
        } else {
            $Reponseform = $this->createForm(ReponseType::class);
            $Reponseform->handleRequest($request);
            $p_difficulte = "Facile";
        }


        $vars = ["Reponseform" => $Reponseform, "scoreEnPourcentage" => $scoreEnPourcentage, "p_difficulte" => $p_difficulte, "liste" => $liste, "langue_cible" => $langueCible, "majStatut" => $majStatut, "i_question" => $i_question, "difficulte" => $difficulte];
        return $this->render('quizz/quizz_questions.html.twig', $vars);
    }
}
