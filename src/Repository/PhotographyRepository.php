<?php

namespace App\Repository;

use App\Entity\Photography;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Photography|null find($id, $lockMode = null, $lockVersion = null)
 * @method Photography|null findOneBy(array $criteria, array $orderBy = null)
 * @method Photography[]    findAll()
 * @method Photography[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhotographyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Photography::class);
    }
    public function getMaxIncrement(){
        $query = $this->createQueryBuilder('photography');
        $query->select('MAX(photography.id) as value');
        return $query->getQuery()->getSingleResult();
    }
}
