<?php

namespace App\Repository;

use App\Entity\Hyperlink;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Hyperlink|null find($id, $lockMode = null, $lockVersion = null)
 * @method Hyperlink|null findOneBy(array $criteria, array $orderBy = null)
 * @method Hyperlink[]    findAll()
 * @method Hyperlink[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HyperlinkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Hyperlink::class);
    }
}
