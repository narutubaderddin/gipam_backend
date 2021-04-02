<?php

namespace App\Repository;

use App\Entity\OeuvreArt;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OeuvreArt|null find($id, $lockMode = null, $lockVersion = null)
 * @method OeuvreArt|null findOneBy(array $criteria, array $orderBy = null)
 * @method OeuvreArt[]    findAll()
 * @method OeuvreArt[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OeuvreArtRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OeuvreArt::class);
    }

    // /**
    //  * @return ArtWork[] Returns an array of ArtWork objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ArtWork
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
