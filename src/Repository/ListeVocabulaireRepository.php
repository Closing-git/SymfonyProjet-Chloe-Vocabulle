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
            $query->andWhere("liste.titre LIKE '%". $filtres['titre'] . "%'");
        }



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
