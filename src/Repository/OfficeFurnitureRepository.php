<?php

namespace App\Repository;

use App\Entity\OfficeFurniture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OfficeFurniture|null find($id, $lockMode = null, $lockVersion = null)
 * @method OfficeFurniture|null findOneBy(array $criteria, array $orderBy = null)
 * @method OfficeFurniture[]    findAll()
 * @method OfficeFurniture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OfficeFurnitureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OfficeFurniture::class);
    }
}
