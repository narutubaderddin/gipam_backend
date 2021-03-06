<?php

namespace App\Repository;

use App\Entity\Denomination;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Denomination|null find($id, $lockMode = null, $lockVersion = null)
 * @method Denomination|null findOneBy(array $criteria, array $orderBy = null)
 * @method Denomination[]    findAll()
 * @method Denomination[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DenominationRepository extends ServiceEntityRepository
{
    use RepositoryTrait;
    const SEARCH_FIELDS = ['label_param' => 'label', 'field_label_param' => 'field_label'];
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Denomination::class);
    }
}
