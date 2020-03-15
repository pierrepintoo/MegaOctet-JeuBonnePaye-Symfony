<?php

namespace App\Repository;

use App\Entity\Jouer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Jouer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Jouer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Jouer[]    findAll()
 * @method Jouer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JouerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Jouer::class);
    }

    // /**
    //  * @return Jouer[] Returns an array of Jouer objects
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
    public function findOneBySomeField($value): ?Jouer
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
