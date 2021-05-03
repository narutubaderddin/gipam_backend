<?php

namespace App\Repository;

use App\Entity\EntryMode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EntryMode|null find($id, $lockMode = null, $lockVersion = null)
 * @method EntryMode|null findOneBy(array $criteria, array $orderBy = null)
 * @method EntryMode[]    findAll()
 * @method EntryMode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EntryModeRepository extends ServiceEntityRepository
{
    use RepositoryTrait;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EntryMode::class);
    }
}
