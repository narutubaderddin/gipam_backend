<?php

namespace App\Repository;

use App\Entity\TypeDeposant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TypeDeposant|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeDeposant|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeDeposant[]    findAll()
 * @method TypeDeposant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeDeposantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeDeposant::class);
    }
}
