<?php

namespace App\Repository;

use App\Entity\Correspondent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Correspondent|null find($id, $lockMode = null, $lockVersion = null)
 * @method Correspondent|null findOneBy(array $criteria, array $orderBy = null)
 * @method Correspondent[]    findAll()
 * @method Correspondent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CorrespondentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Correspondent::class);
    }

    // /**
    //  * @return Correspondent[] Returns an array of Correspondent objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Correspondent
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
