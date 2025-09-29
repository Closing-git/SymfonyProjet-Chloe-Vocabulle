<?php

namespace App\Repository;

use App\Entity\Utilisateur;
use App\Entity\ListeVocabulaire;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

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
        $em = $this->getEntityManager();

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

        //Filtrer par langue
        if ($filtres['langue']) {
            $query->andWhere('langues = :langue')
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


        //EQUIVALENT
        // $query = $em->createQuery(
        //     "SELECT liste, langues, createur, note, infosJeux
        // FROM App\Entity\ListeVocabulaire liste
        // LEFT JOIN liste.note note
        // LEFT JOIN liste.langues langues
        // LEFT JOIN liste.createur createur
        // LEFT JOIN liste.infosJeux infosJeux);



        $res = $query->getQuery()->getResult();
        return $res;
    }



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
}
