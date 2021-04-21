<?php

namespace App\Repository;

use App\Entity\PropertyStatusCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PropertyStatusCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method PropertyStatusCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method PropertyStatusCategory[]    findAll()
 * @method PropertyStatusCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PropertyStatusCategoryRepository extends ServiceEntityRepository
{
    use RepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PropertyStatusCategory::class);
    }
}
