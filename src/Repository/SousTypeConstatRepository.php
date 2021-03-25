<?php

namespace App\Repository;

use App\Entity\SousTypeConstat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SousTypeConstat|null find($id, $lockMode = null, $lockVersion = null)
 * @method SousTypeConstat|null findOneBy(array $criteria, array $orderBy = null)
 * @method SousTypeConstat[]    findAll()
 * @method SousTypeConstat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SousTypeConstatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SousTypeConstat::class);
    }

    // /**
    //  * @return ReportSubType[] Returns an array of ReportSubType objects
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
    public function findOneBySomeField($value): ?ReportSubType
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
