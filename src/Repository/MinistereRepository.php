<?php

namespace App\Repository;

use App\Entity\Ministere;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Ministere|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ministere|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ministere[]    findAll()
 * @method Ministere[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MinistereRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ministere::class);
    }

    // /**
    //  * @return Ministry[] Returns an array of Ministry objects
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
    public function findOneBySomeField($value): ?Ministry
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
