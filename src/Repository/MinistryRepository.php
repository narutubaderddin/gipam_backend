<?php

namespace App\Repository;

use App\Entity\Ministry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Ministry|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ministry|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ministry[]    findAll()
 * @method Ministry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MinistryRepository extends ServiceEntityRepository
{
    use RepositoryTrait;

    public const SEARCH_FIELDS = [
        'name_param' => 'name',
        'acronym_param'=>'acronym'
    ];

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ministry::class);
    }
}
