<?php

namespace App\Repository;

use App\Entity\LocationType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LocationType|null find($id, $lockMode = null, $lockVersion = null)
 * @method LocationType|null findOneBy(array $criteria, array $orderBy = null)
 * @method LocationType[]    findAll()
 * @method LocationType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LocationTypeRepository extends ServiceEntityRepository
{
    use RepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LocationType::class);
    }
}
