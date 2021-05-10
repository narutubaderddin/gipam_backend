<?php

namespace App\Repository;

use App\Entity\PhotographyType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PhotographyType|null find($id, $lockMode = null, $lockVersion = null)
 * @method PhotographyType|null findOneBy(array $criteria, array $orderBy = null)
 * @method PhotographyType[]    findAll()
 * @method PhotographyType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhotographyTypeRepository extends ServiceEntityRepository
{
    use RepositoryTrait;

    public const SEARCH_FIELDS = ['type_param' => 'type'];

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PhotographyType::class);
    }
}
