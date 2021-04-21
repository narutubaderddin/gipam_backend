<?php

namespace App\Repository;

use App\Entity\AuthorType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AuthorType|null find($id, $lockMode = null, $lockVersion = null)
 * @method AuthorType|null findOneBy(array $criteria, array $orderBy = null)
 * @method AuthorType[]    findAll()
 * @method AuthorType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthorTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AuthorType::class);
    }
}
