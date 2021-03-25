<?php

namespace App\Repository;

use App\Entity\TypeMouvement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TypeMouvement|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeMouvement|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeMouvement[]    findAll()
 * @method TypeMouvement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeMouvementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeMouvement::class);
    }

    // /**
    //  * @return MovementType[] Returns an array of MovementType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MovementType
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
