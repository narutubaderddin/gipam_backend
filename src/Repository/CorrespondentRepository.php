<?php

namespace App\Repository;

use App\Entity\Correspondent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Correspondent|null find($id, $lockMode = null, $lockVersion = null)
 * @method Correspondent|null findOneBy(array $criteria, array $orderBy = null)
 * @method Correspondent[]    findAll()
 * @method Correspondent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CorrespondentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Correspondent::class);
    }
}
