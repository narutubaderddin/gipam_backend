<?php

namespace App\Repository;

use App\Entity\MovementActionType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MovementActionType|null find($id, $lockMode = null, $lockVersion = null)
 * @method MovementActionType|null findOneBy(array $criteria, array $orderBy = null)
 * @method MovementActionType[]    findAll()
 * @method MovementActionType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovementActionTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MovementActionType::class);
    }

    // /**
    //  * @return MovementActionType[] Returns an array of MovementActionType objects
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
    public function findOneBySomeField($value): ?MovementActionType
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
