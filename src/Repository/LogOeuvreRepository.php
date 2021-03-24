<?php

namespace App\Repository;

use App\Entity\LogOeuvre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LogOeuvre|null find($id, $lockMode = null, $lockVersion = null)
 * @method LogOeuvre|null findOneBy(array $criteria, array $orderBy = null)
 * @method LogOeuvre[]    findAll()
 * @method LogOeuvre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LogOeuvreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LogOeuvre::class);
    }
}
