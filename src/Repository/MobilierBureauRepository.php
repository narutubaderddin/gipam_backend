<?php

namespace App\Repository;

use App\Entity\MobilierBureau;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MobilierBureau|null find($id, $lockMode = null, $lockVersion = null)
 * @method MobilierBureau|null findOneBy(array $criteria, array $orderBy = null)
 * @method MobilierBureau[]    findAll()
 * @method MobilierBureau[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MobilierBureauRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MobilierBureau::class);
    }

    // /**
    //  * @return OfficeFurniture[] Returns an array of OfficeFurniture objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OfficeFurniture
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
