<?php

namespace App\Repository;

use App\Entity\ObjetMobilier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ObjetMobilier|null find($id, $lockMode = null, $lockVersion = null)
 * @method ObjetMobilier|null findOneBy(array $criteria, array $orderBy = null)
 * @method ObjetMobilier[]    findAll()
 * @method ObjetMobilier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ObjetMobilierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ObjetMobilier::class);
    }

    // /**
    //  * @return Furniture[] Returns an array of Furniture objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Furniture
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
