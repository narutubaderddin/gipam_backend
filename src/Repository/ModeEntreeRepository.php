<?php

namespace App\Repository;

use App\Entity\ModeEntree;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ModeEntree|null find($id, $lockMode = null, $lockVersion = null)
 * @method ModeEntree|null findOneBy(array $criteria, array $orderBy = null)
 * @method ModeEntree[]    findAll()
 * @method ModeEntree[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModeEntreeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ModeEntree::class);
    }

    // /**
    //  * @return EntryMode[] Returns an array of EntryMode objects
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
    public function findOneBySomeField($value): ?EntryMode
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
