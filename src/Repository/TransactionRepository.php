<?php

namespace App\Repository;

use App\Entity\Transaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Transaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Transaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Transaction[]    findAll()
 * @method Transaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Transaction::class);
    }

    // /**
    //  * @return Transaction[] Returns an array of Transaction objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Transaction
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /*public function transactionUser($userEnvoie, $userRetrait): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT *
            FROM App\Entity\Transaction t
            WHERE t.userEnvoie = :userEnvoie
            OR t.userRetrait = :userRetrait
            ORDER BY p.price ASC'
        )->setParameter('userEnvoie', $userEnvoie);
         ->setParameter('userRetrait', $userRetrait);

        // returns an array of Product objects
        return $query->execute();
    }*/

    
    /**
      * @return Transaction[] Returns an array of Transaction objects
      */
      public function transactionParDateUser($datedebut, $datefin, $userconnect)
      {
          return $this->createQueryBuilder('transactions')
              ->andWhere('transactions.dateEnvoie >= :dateEnvoie')
              ->andWhere('transactions.dateEnvoie = :dateRetrait')
              ->andWhere('transactions.utilisateur = :userconnect')
              ->ordWhere('transactions.userRetrait = :userconnect')
              ->setParameter('dateEnvoie', $datedebut)
              ->setParameter('dateRetrait', $datefin)
              ->setParameter('userconnect', $userconnect)
              ->orderBy('transaction.id', 'ASC')
              ->getQuery()
              ->getResult()
          ;
      }

    /**
      * @return Transaction[] Returns an array of Transaction objects
      */
      public function transactionParDate($datedebut, $datefin)
      {
          return $this->createQueryBuilder('transaction')
              ->andWhere('transaction.dateEnvoie >= :dateEnvoie')
              ->andWhere('transaction.dateEnvoie <= :dateRetrait')
              ->setParameter('dateEnvoie', $datedebut)
              ->setParameter('dateRetrait', $datefin)
              ->orderBy('t.id', 'ASC')
              ->getQuery()
              ->getResult()
          ;
      }

     /**
      * @return Transaction[] Returns an array of Transaction objects
      */
    
    public function transactionUserMoimeme($userEnvoie)
    {
        return $this->createQueryBuilder('transaction')
            ->andWhere('transaction.utilisateur = :userEnvoie')
            ->orWhere('transaction.userRetrait = :userEnvoie')
            ->setParameter('userEnvoie', $userEnvoie)
            ->getQuery()
            ->getResult()
        ;
    }
}
