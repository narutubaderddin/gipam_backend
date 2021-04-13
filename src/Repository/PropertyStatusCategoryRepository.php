<?php

namespace App\Repository;

use App\Entity\PropertyStatusCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PropertyStatusCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method PropertyStatusCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method PropertyStatusCategory[]    findAll()
 * @method PropertyStatusCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PropertyStatusCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PropertyStatusCategory::class);
    }

    // /**
    //  * @return PropertyStatusCategory[] Returns an array of PropertyStatusCategory objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PropertyStatusCategory
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
