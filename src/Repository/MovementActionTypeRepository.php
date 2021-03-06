<?php

namespace App\Repository;

use App\Entity\MovementActionType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MovementActionType|null find($id, $lockMode = null, $lockVersion = null)
 * @method MovementActionType|null findOneBy(array $criteria, array $orderBy = null)
 * @method MovementActionType[]    findAll()
 * @method MovementActionType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovementActionTypeRepository extends ServiceEntityRepository
{
    use RepositoryTrait;

    public const SEARCH_FIELDS = [
        'label_param' => 'label',
        'movementType_label_param'=>'movementType_label'
    ];

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MovementActionType::class);
    }
}
