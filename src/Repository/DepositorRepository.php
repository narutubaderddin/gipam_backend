<?php

namespace App\Repository;

use App\Entity\Depositor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Depositor|null find($id, $lockMode = null, $lockVersion = null)
 * @method Depositor|null findOneBy(array $criteria, array $orderBy = null)
 * @method Depositor[]    findAll()
 * @method Depositor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DepositorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Depositor::class);
    }

    // /**
    //  * @return Depositor[] Returns an array of Depositor objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Depositor
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
