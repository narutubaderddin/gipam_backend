<?php

namespace App\Repository;

use App\Entity\Denomination;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Denomination|null find($id, $lockMode = null, $lockVersion = null)
 * @method Denomination|null findOneBy(array $criteria, array $orderBy = null)
 * @method Denomination[]    findAll()
 * @method Denomination[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DenominationRepository extends ServiceEntityRepository
{
    use RepositoryTrait;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Denomination::class);
    }

    // /**
    //  * @return Denomination[] Returns an array of Denomination objects
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
    public function findOneBySomeField($value): ?Denomination
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
