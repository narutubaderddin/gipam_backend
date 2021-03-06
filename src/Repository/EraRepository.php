<?php

namespace App\Repository;

use App\Entity\Era;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Era|null find($id, $lockMode = null, $lockVersion = null)
 * @method Era|null findOneBy(array $criteria, array $orderBy = null)
 * @method Era[]    findAll()
 * @method Era[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EraRepository extends ServiceEntityRepository
{
    use RepositoryTrait;

    public const SEARCH_FIELDS = ['label_param' => 'label'];

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Era::class);
    }
}
