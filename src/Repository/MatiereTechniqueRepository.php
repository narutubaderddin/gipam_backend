<?php

namespace App\Repository;

use App\Entity\MatiereTechnique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MatiereTechnique|null find($id, $lockMode = null, $lockVersion = null)
 * @method MatiereTechnique|null findOneBy(array $criteria, array $orderBy = null)
 * @method MatiereTechnique[]    findAll()
 * @method MatiereTechnique[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MatiereTechniqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MatiereTechnique::class);
    }
}
