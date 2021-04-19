<?php

namespace App\Repository;

use App\Entity\Denomination;
use App\Entity\MaterialTechnique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MaterialTechnique|null find($id, $lockMode = null, $lockVersion = null)
 * @method MaterialTechnique|null findOneBy(array $criteria, array $orderBy = null)
 * @method MaterialTechnique[]    findAll()
 * @method MaterialTechnique[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MaterialTechniqueRepository extends ServiceEntityRepository
{
    use RepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MaterialTechnique::class);
    }

    public function findByLabelAndDenomination(string $label, Denomination $denomination)
    {
        $query = $this->createQueryBuilder('mt')
            ->leftJoin('mt.denominations', 'denominations')
            ->where('mt.label = :label')
            ->andWhere('denominations.id = :denominationId')
            ->setParameters(['label' => $label, 'denominationId' => $denomination->getId()])
            ->getQuery()
            ->getOneOrNullResult();
    }
}
