<?php

namespace App\Repository;

use App\Entity\Epoque;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Epoque|null find($id, $lockMode = null, $lockVersion = null)
 * @method Epoque|null findOneBy(array $criteria, array $orderBy = null)
 * @method Epoque[]    findAll()
 * @method Epoque[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EpoqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Epoque::class);
    }
}
