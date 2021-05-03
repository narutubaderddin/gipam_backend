<?php

namespace App\Repository;

use App\Entity\MovementType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MovementType|null find($id, $lockMode = null, $lockVersion = null)
 * @method MovementType|null findOneBy(array $criteria, array $orderBy = null)
 * @method MovementType[]    findAll()
 * @method MovementType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovementTypeRepository extends ServiceEntityRepository
{
    use RepositoryTrait;

    public const SEARCH_FIELDS = [
        'label_param' => 'label'
    ];

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MovementType::class);
    }
}
