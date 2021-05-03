<?php

namespace App\Repository;

use App\Entity\ReportType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReportType|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReportType|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReportType[]    findAll()
 * @method ReportType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReportTypeRepository extends ServiceEntityRepository
{
    use RepositoryTrait;

    public const SEARCH_FIELDS = ['label_param' => 'label'];

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReportType::class);
    }
}
