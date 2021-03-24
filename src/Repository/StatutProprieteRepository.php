<?php

namespace App\Repository;

use App\Entity\StatutPropriete;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StatutPropriete|null find($id, $lockMode = null, $lockVersion = null)
 * @method StatutPropriete|null findOneBy(array $criteria, array $orderBy = null)
 * @method StatutPropriete[]    findAll()
 * @method StatutPropriete[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatutProprieteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StatutPropriete::class);
    }
}
