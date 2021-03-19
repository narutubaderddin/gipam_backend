<?php

namespace App\Repository;

use App\Entity\SubDivision;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SubDivision|null find($id, $lockMode = null, $lockVersion = null)
 * @method SubDivision|null findOneBy(array $criteria, array $orderBy = null)
 * @method SubDivision[]    findAll()
 * @method SubDivision[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubDivisionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SubDivision::class);
    }

    // /**
    //  * @return SubDivision[] Returns an array of SubDivision objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SubDivision
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
