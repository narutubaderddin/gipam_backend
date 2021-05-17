<?php

namespace App\Repository;

use App\Entity\Reserve;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Reserve|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reserve|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reserve[]    findAll()
 * @method Reserve[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReserveRepository extends ServiceEntityRepository
{
    use RepositoryTrait;

    public const SEARCH_FIELDS = [
        'label_param' => 'label',
        'room_reference_param' => 'room_reference'
    ];

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reserve::class);
    }
}
