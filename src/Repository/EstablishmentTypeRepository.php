<?php

namespace App\Repository;

use App\Entity\EstablishmentType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EstablishmentType|null find($id, $lockMode = null, $lockVersion = null)
 * @method EstablishmentType|null findOneBy(array $criteria, array $orderBy = null)
 * @method EstablishmentType[]    findAll()
 * @method EstablishmentType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EstablishmentTypeRepository extends ServiceEntityRepository
{
    use RepositoryTrait;

    public const SEARCH_FIELDS = ['label_param' => 'label'];

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EstablishmentType::class);
    }
}
