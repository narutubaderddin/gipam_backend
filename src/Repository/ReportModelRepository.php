<?php

namespace App\Repository;

use App\Entity\ReportModel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReportModel|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReportModel|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReportModel[]    findAll()
 * @method ReportModel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReportModelRepository extends ServiceEntityRepository
{
    use RepositoryTrait;

    public const SEARCH_FIELDS = ['name_param' => 'name',];

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReportModel::class);
    }
}
