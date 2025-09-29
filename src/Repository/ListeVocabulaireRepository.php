<?php

namespace App\Repository;

use App\Entity\ListeVocabulaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ListeVocabulaire>
 */
class ListeVocabulaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ListeVocabulaire::class);
    }


    public function searchListes($filtres)
    {
        $em = $this->getEntityManager();




        $query = $em->createQuery(
            "SELECT liste, langues, createur, note, infosJeux
        FROM App\Entity\ListeVocabulaire liste
        LEFT JOIN liste.note note
        LEFT JOIN liste.langues langues
        LEFT JOIN liste.createur createur
        LEFT JOIN liste.infosJeux infosJeux

                -- INNER JOIN liste.utilisateurs_qui_fav utilisateur
                -- WHERE langue = :langue
                -- AND
                -- WHERE statut = :statut
                "
        );

        // $query->setParameter(":langue", $filtres['langue']);
        // $query->setParameter(":statut", $filtres['statut']);

        $res = $query->getResult();
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
