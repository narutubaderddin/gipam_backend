<?php

namespace App\Repository;

use App\Entity\DepositStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DepositStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method DepositStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method DepositStatus[]    findAll()
 * @method DepositStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DepositStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DepositStatus::class);
    }
}
