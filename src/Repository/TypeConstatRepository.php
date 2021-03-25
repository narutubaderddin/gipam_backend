<?php

namespace App\Repository;

use App\Entity\TypeConstat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TypeConstat|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeConstat|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeConstat[]    findAll()
 * @method TypeConstat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeConstatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeConstat::class);
    }

    // /**
    //  * @return ReportType[] Returns an array of ReportType objects
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
    public function findOneBySomeField($value): ?ReportType
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
