<?php

namespace App\Repository;

use App\Entity\Utilisateur;
use App\Entity\ListeVocabulaire;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use PhpParser\Node\Stmt\ElseIf_;

/**
 * @extends ServiceEntityRepository<ListeVocabulaire>
 */
class ListeVocabulaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ListeVocabulaire::class);
    }


    public function searchListes(array $filtres, Utilisateur $user)
    {

        $query = $this->createQueryBuilder('liste')
            ->leftJoin('liste.note', 'note')->addSelect('note')
            ->leftJoin('liste.langues', 'langues')->addSelect('langues')
            ->leftJoin('liste.createur', 'createur')->addSelect('createur')
            ->leftJoin('liste.infosJeux', 'infosJeux')->addSelect('infosJeux')
            ->leftJoin('liste.utilisateursQuiFav', 'utilisateursQuiFav')->addSelect('utilisateursQuiFav');


        // Filtrer par Créé par moi
        if ($filtres['ownCreator']) {
            $query->andWhere('liste.createur = :user')
                ->setParameter('user', $user);
        }

        //Filtrer par langue (Doit utiliser MEMBER OF parce que c'est une relation many to many et qu'on veut avoir accès aux deux langues)
        if ($filtres['langue']) {
            $query->andWhere(':langue MEMBER OF liste.langues')
                ->setParameter('langue', $filtres['langue']);
        }

        // Public = true / Privé = false
        if ($filtres['statut'] == 'public' and $filtres['statut'] != '') {
            $query->andWhere('liste.publicStatut = True');
        }
        if ($filtres['statut'] == "prive") {
            $query->andWhere('liste.publicStatut = False');
        }

        //Filtrer par Favoris (Doit utiliser MEMBER OF parce que c'est une relation many to many)
        if ($filtres['fav']) {
            $query->andWhere(':user2 MEMBER OF liste.utilisateursQuiFav')
                ->setParameter('user2', $user);
        }

        //Filtrer par titre : barre de recherche
        if ($filtres['titre']) {
            $query->andWhere("liste.titre LIKE '%" . $filtres['titre'] . "%'");
        }


        if ($filtres['ordre'] == 'alpha') {
            $query->orderBy("liste.titre", "ASC");
        } elseif ($filtres['ordre'] == 'antiAlpha') {
            $query->orderBy("liste.titre", "DESC");
        } elseif ($filtres['ordre'] == 'olderFirst') {
            $query->orderBy("liste.dateDerniereModif", "DESC");
        } elseif ($filtres['ordre'] == 'newerFirst') {
            $query->orderBy("liste.dateDerniereModif", "ASC");
        }elseif ($filtres['ordre'] == 'bestNoteFirst') {
                //AVG : fait la moyenne de la colonne de la table
                //COALESCE : remplace null par un nb (ici 0), si la colonne est NULL
                //AS HIDDEN : donne un alias à avgNote mais la 'cache' pour pas que Doctrine ne le renvoie
                $query
                    ->addSelect('(SELECT COALESCE(AVG(no.montantNote), 0) 
                                FROM App\Entity\Note no 
                                WHERE no.listeVocabulaire = liste) AS HIDDEN avgNote')
                    ->orderBy('avgNote', 'DESC');
        }
        elseif ($filtres['ordre'] == 'bestScoreFirst'){
            $query->addSelect('(SELECT COALESCE(info.bestScoreMostDifficult,0)
            FROM App\Entity\InfosJeu info
            WHERE info.utilisateur = :user3 
            AND info.listeVocabulaire = liste) AS HIDDEN bestScoreMostDifficult')
            ->setParameter('user3', $user)
            ->orderBy('bestScoreMostDifficult', 'DESC');
        }
        elseif ($filtres['ordre'] == 'worseScoreFirst'){
            $query->addSelect('(SELECT COALESCE(info.bestScoreMostDifficult,0)
            FROM App\Entity\InfosJeu info
            WHERE info.utilisateur = :user3 
            AND info.listeVocabulaire = liste) AS HIDDEN bestScoreMostDifficult')
            ->setParameter('user3', $user)
            ->orderBy('bestScoreMostDifficult', 'ASC');
        }

        $res = $query->getQuery()->getResult();
        return $res;
    }}



    //    /**
    //     * @return ListeVocabulaire[] Returns an array of ListeVocabulaire objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('l.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?ListeVocabulaire
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

