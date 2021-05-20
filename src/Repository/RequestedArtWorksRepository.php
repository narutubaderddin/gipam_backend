<?php

namespace App\Repository;

use App\Entity\RequestedArtWorks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RequestedArtWorks|null find($id, $lockMode = null, $lockVersion = null)
 * @method RequestedArtWorks|null findOneBy(array $criteria, array $orderBy = null)
 * @method RequestedArtWorks[]    findAll()
 * @method RequestedArtWorks[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RequestedArtWorksRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RequestedArtWorks::class);
    }

    // /**
    //  * @return RequestedArtWorks[] Returns an array of RequestedArtWorks objects
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
    public function findOneBySomeField($value): ?RequestedArtWorks
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
