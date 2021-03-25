<?php

namespace App\Repository;

use App\Entity\TypeEtablissement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TypeEtablissement|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeEtablissement|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeEtablissement[]    findAll()
 * @method TypeEtablissement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeEtablissementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeEtablissement::class);
    }

    // /**
    //  * @return EstablishmentType[] Returns an array of EstablishmentType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EstablishmentType
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
