<?php

namespace App\Repository;

use App\Entity\Jouers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Jouers|null find($id, $lockMode = null, $lockVersion = null)
 * @method Jouers|null findOneBy(array $criteria, array $orderBy = null)
 * @method Jouers[]    findAll()
 * @method Jouers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JouersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Jouers::class);
    }

    // /**
    //  * @return Jouers[] Returns an array of Jouers objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('j.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Jouers
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
