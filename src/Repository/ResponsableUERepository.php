<?php

namespace App\Repository;

use App\Entity\ResponsableUE;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ResponsableUE|null find($id, $lockMode = null, $lockVersion = null)
 * @method ResponsableUE|null findOneBy(array $criteria, array $orderBy = null)
 * @method ResponsableUE[]    findAll()
 * @method ResponsableUE[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResponsableUERepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ResponsableUE::class);
    }

    // /**
    //  * @return ResponsableUE[] Returns an array of ResponsableUE objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ResponsableUE
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
