<?php

namespace App\Repository;

use App\Entity\Building;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use FOS\RestBundle\Request\ParamFetcherInterface;

/**
 * @method Building|null find($id, $lockMode = null, $lockVersion = null)
 * @method Building|null findOneBy(array $criteria, array $orderBy = null)
 * @method Building[]    findAll()
 * @method Building[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BuildingRepository extends ServiceEntityRepository
{
    use RepositoryTrait;

    public const SEARCH_FIELDS = [
        'name_param' => 'name',
        'address_param' => 'address',
        'cedex_param' => 'cedex',
        'distrib_param' => 'distrib'
    ];

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Building::class);
    }

    public function findRecordsByEntityNameAndCriteria(ParamFetcherInterface $paramFetcher, $count, $page = 1, $limit = 0)
    {
        $name = $paramFetcher->get('search')??"";
        $departement = $paramFetcher->get('departement') ?? "";
        $region = $paramFetcher->get('region') ?? "";
        $commune = $paramFetcher->get('commune') ?? "";
        $query = $this->createQueryBuilder('building')
            ->leftJoin('building.commune', 'commune')
            ->leftJoin('commune.department', 'departement')
            ->leftJoin('departement.region', 'region');
        if($name!=""){
            $query = $this->andWhere($query,'name','contains','name',$name);
        }
        $query = $this->addArrayCriteriaCondition($query, $departement, 'departement');
        $query = $this->addArrayCriteriaCondition($query, $region, 'region');
        $query = $this->addArrayCriteriaCondition($query, $commune, 'commune');

        if ($count) {
            $query->select('count(commune.id)');
            return $query->getQuery()->getSingleScalarResult();
        }

        if ($page != "") {
            $query->setFirstResult(($page * $limit) + 1);
        }
        if ($limit && $limit != "") {
            $query->setMaxResults($limit);
        }

        return $query->getQuery()->getResult();
    }
}
