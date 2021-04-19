<?php

namespace App\Repository;

use App\Entity\ActionReportType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ActionReportType|null find($id, $lockMode = null, $lockVersion = null)
 * @method ActionReportType|null findOneBy(array $criteria, array $orderBy = null)
 * @method ActionReportType[]    findAll()
 * @method ActionReportType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActionReportTypeRepository extends ServiceEntityRepository
{
    use RepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActionReportType::class);
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
