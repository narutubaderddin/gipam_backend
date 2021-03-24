<?php

namespace App\Repository;

use App\Entity\AuteurType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AuteurType|null find($id, $lockMode = null, $lockVersion = null)
 * @method AuteurType|null findOneBy(array $criteria, array $orderBy = null)
 * @method AuteurType[]    findAll()
 * @method AuteurType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuteurTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AuteurType::class);
    }
}
