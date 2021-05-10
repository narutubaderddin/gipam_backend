<?php

namespace App\Repository;

use App\Entity\PropertyStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PropertyStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method PropertyStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method PropertyStatus[]    findAll()
 * @method PropertyStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PropertyStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PropertyStatus::class);
    }

    public function getDescriptionAutocompleteData(string $searchQuery){
        $query = $this->createQueryBuilder('property_status')
            ->where('LOWER(property_status.descriptiveWords) like :param')
            ->setParameter('param',strtolower("%".$searchQuery."%"))
            ->select('property_status.descriptiveWords');
        return $query->getQuery()->getResult();
    }
}
