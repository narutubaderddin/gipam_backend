<?php

namespace App\Repository;

use App\Entity\ActionReportType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ActionReportType|null find($id, $lockMode = null, $lockVersion = null)
 * @method ActionReportType|null findOneBy(array $criteria, array $orderBy = null)
 * @method ActionReportType[]    findAll()
 * @method ActionReportType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActionReportTypeRepository extends ServiceEntityRepository
{
    use RepositoryTrait;

    public const SEARCH_FIELDS = ['label_param' => 'label'];

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActionReportType::class);
    }
}
