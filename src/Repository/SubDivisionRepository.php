<?php

namespace App\Repository;

use App\Entity\SubDivision;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SubDivision|null find($id, $lockMode = null, $lockVersion = null)
 * @method SubDivision|null findOneBy(array $criteria, array $orderBy = null)
 * @method SubDivision[]    findAll()
 * @method SubDivision[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubDivisionRepository extends ServiceEntityRepository
{
    use RepositoryTrait;

    public const SEARCH_FIELDS = ['label_param' => 'label', 'acronym_param' => 'acronym'];

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SubDivision::class);
    }
}
