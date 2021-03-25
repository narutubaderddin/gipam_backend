<?php

namespace App\Repository;

use App\Entity\StatutDepot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StatutDepot|null find($id, $lockMode = null, $lockVersion = null)
 * @method StatutDepot|null findOneBy(array $criteria, array $orderBy = null)
 * @method StatutDepot[]    findAll()
 * @method StatutDepot[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatutDepotRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StatutDepot::class);
    }
}
