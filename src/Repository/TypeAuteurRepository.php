<?php

namespace App\Repository;

use App\Entity\TypeAuteur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TypeAuteur|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeAuteur|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeAuteur[]    findAll()
 * @method TypeAuteur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeAuteurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeAuteur::class);
    }
}
