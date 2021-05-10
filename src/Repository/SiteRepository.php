<?php

namespace App\Repository;

use App\Entity\Site;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use FOS\RestBundle\Request\ParamFetcherInterface;

/**
 * @method Site|null find($id, $lockMode = null, $lockVersion = null)
 * @method Site|null findOneBy(array $criteria, array $orderBy = null)
 * @method Site[]    findAll()
 * @method Site[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SiteRepository extends ServiceEntityRepository
{
    use RepositoryTrait;

    public const SEARCH_FIELDS = ['label_param' => 'label'];

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Site::class);
    }
    public function findRecordsByEntityNameAndCriteria(ParamFetcherInterface $paramFetcher, $count, $page = 1, $limit = 0)
    {
        $name = $paramFetcher->get('search')??"";
        $departement = $paramFetcher->get('departement') ?? "";
        $region = $paramFetcher->get('region') ?? "";
        $commune = $paramFetcher->get('commune') ?? "";
        $batiment = $paramFetcher->get('batiment')??"";
        $query = $this->createQueryBuilder('site')
            ->leftJoin('site.buildings','building')
            ->leftJoin('building.commune','commune')
            ->leftJoin('commune.department','departement')
            ->leftJoin('departement.region','region')
            ;
        if($name!=""){
            $query = $this->andWhere($query,'label','contains','label',$name);
        }
        $query = $this->addArrayCriteriaCondition($query, $departement, 'departement');
        $query = $this->addArrayCriteriaCondition($query, $region, 'region');
        $query = $this->addArrayCriteriaCondition($query, $commune, 'commune');
        $query = $this->addArrayCriteriaCondition($query, $batiment, 'building');

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
