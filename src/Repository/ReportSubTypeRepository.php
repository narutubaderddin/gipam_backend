<?php

namespace App\Repository;

use App\Entity\ReportSubType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReportSubType|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReportSubType|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReportSubType[]    findAll()
 * @method ReportSubType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReportSubTypeRepository extends ServiceEntityRepository
{
    use RepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReportSubType::class);
    }
}
