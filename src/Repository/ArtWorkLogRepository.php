<?php

namespace App\Repository;

use App\Entity\ArtWorkLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ArtWorkLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method ArtWorkLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method ArtWorkLog[]    findAll()
 * @method ArtWorkLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArtWorkLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ArtWorkLog::class);
    }
}
