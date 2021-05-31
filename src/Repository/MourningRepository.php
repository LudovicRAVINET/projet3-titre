<?php

namespace App\Repository;

use App\Entity\Mourning;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Mourning|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mourning|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mourning[]    findAll()
 * @method Mourning[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MourningRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mourning::class);
    }

    // /**
    //  * @return Mourning[] Returns an array of Mourning objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Mourning
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
