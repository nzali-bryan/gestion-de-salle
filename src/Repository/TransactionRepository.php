<?php

namespace App\Repository;

use App\Entity\Transaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Transaction>
 */
class TransactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transaction::class);
    }

    public function getDepREv(): array
    {
        $aujourdhuiDebut = new \DateTimeImmutable('today 00:00:00');
        $aujourdhuiFin = new \DateTimeImmutable('today 23:59:59');

        return $this->createQueryBuilder('t')
            ->select('t.type, SUM(t.montant) as total')
            ->where('t.creatadAt BETWEEN :debut AND :fin')
            ->setParameter('debut', $aujourdhuiDebut)
            ->setParameter('fin', $aujourdhuiFin)
            ->groupBy('t.type')
            ->getQuery()
            ->getResult();
    }


//   Récupère la dernière transaction avec sa Session et sa Console associées
 
    public function findDerniereTrans(): ?Transaction
    {
        return $this->createQueryBuilder('t')
            ->leftJoin('t.Sessions', 's')
            ->addSelect('s') // Force le chargement des données de la session
            

            ->leftJoin('s.Console', 'c')
            ->addSelect('c')
            
            ->orderBy('t.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

//   Récupère les revenus (entrées) par console pour le jour, la semaine et le mois en cours
 
public function findRevenusParConsole(): array
{

    $aujourdhui = new \DateTimeImmutable('today 00:00:00');
    
    $debutSemaine = new \DateTimeImmutable('monday this week 00:00:00');
    $debutMois = new \DateTimeImmutable('first day of this month 00:00:00');

    return $this->createQueryBuilder('t')
        ->select('c.nom AS consoleNom') // Adapte 'nom' selon ta propriété dans l'entité Console
        ->addSelect("SUM(CASE WHEN t.creatadAt >= :aujourdhui THEN t.montant ELSE 0 END) as jour")
        ->addSelect("SUM(CASE WHEN t.creatadAt >= :debutSemaine THEN t.montant ELSE 0 END) as semaine")
        ->addSelect("SUM(CASE WHEN t.creatadAt >= :debutMois THEN t.montant ELSE 0 END) as mois")
        // Jointures pour atteindre la console
        ->join('t.Sessions', 's')
        ->join('s.Console', 'c')
        // On ne cible que l'argent généré (les ventes/entrées)
        ->where("t.type = 'entrée'") 
        ->groupBy('c.id')
        ->setParameter('aujourdhui', $aujourdhui)
        ->setParameter('debutSemaine', $debutSemaine)
        ->setParameter('debutMois', $debutMois)
        ->getQuery()
        ->getResult();
}


public function findBilan(\DateTimeInterface $debut, \DateTimeInterface $fin): array
{
    // On s'assure que les dates couvrent bien toute la journée du début à la fin
    $dateDebut = \DateTimeImmutable::createFromInterface($debut)->setTime(0, 0, 0);
    $dateFin = \DateTimeImmutable::createFromInterface($fin)->setTime(23, 59, 59);

    return $this->createQueryBuilder('t')
        ->select("SUM(CASE WHEN t.type = 'entrée' THEN t.montant ELSE 0 END) as totalEntrees")
        ->addSelect("SUM(CASE WHEN t.type = 'sortie' THEN t.montant ELSE 0 END) as totalSorties")
        // Compte le nombre de transactions uniques liées à une session de jeu
        ->addSelect("COUNT(DISTINCT t.Sessions) as totalSessionsJouees")
        // Compte le nombre total de mouvements (entrées + sorties)
        ->addSelect("COUNT(t.id) as totalMouvements")
        ->where('t.creatadAt BETWEEN :debut AND :fin')
        ->setParameter('debut', $dateDebut)
        ->setParameter('fin', $dateFin)
        ->getQuery()
        ->getSingleResult(); // Retourne un tableau unique avec nos statistiques
}

//    /**
//     * @return Transaction[] Returns an array of Transaction objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Transaction
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
