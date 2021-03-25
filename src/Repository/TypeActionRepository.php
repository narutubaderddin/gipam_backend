<?php

namespace App\Repository;

use App\Entity\TypeAction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TypeAction|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeAction|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeAction[]    findAll()
 * @method TypeAction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeActionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeAction::class);
    }

    // /**
    //  * @return ActionType[] Returns an array of ActionType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ActionType
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
