<?php

namespace App\Repository;

use App\Entity\Deposant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Deposant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Deposant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Deposant[]    findAll()
 * @method Deposant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeposantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Deposant::class);
    }
}
