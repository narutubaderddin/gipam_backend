<?php

namespace App\Repository;

use App\Entity\TypeLocalisation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TypeLocalisation|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeLocalisation|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeLocalisation[]    findAll()
 * @method TypeLocalisation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeLocalisationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeLocalisation::class);
    }

    // /**
    //  * @return LocationType[] Returns an array of LocationType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LocationType
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
