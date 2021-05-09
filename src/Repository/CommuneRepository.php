<?php

namespace App\Repository;

use App\Entity\Commune;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use FOS\RestBundle\Request\ParamFetcherInterface;

/**
 * @method Commune|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commune|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commune[]    findAll()
 * @method Commune[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommuneRepository extends ServiceEntityRepository
{
    use RepositoryTrait;

    public const SEARCH_FIELDS = ['name_param' => 'name'];

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commune::class);
    }

    public function findRecordsByEntityNameAndCriteria(ParamFetcherInterface $paramFetcher,$count,$page=1,$limit=0){
       $name = $paramFetcher->get('search')??"";
       $departement =$paramFetcher->get('departement')??"";
       $region =$paramFetcher->get('region')??"";
       $query = $this->createQueryBuilder('commune')
                     ->leftJoin('commune.department','departement')
                     ->leftJoin('departement.region','region');
        if($name!=""){
            $query = $this->andWhere($query,'name','contains','name',$name);
        }
        $query = $this->addArrayCriteriaCondition($query, $departement, 'departement');
        $query = $this->addArrayCriteriaCondition($query, $region, 'region');

        if($count){
            $query->select('count(commune.id)');
            return $query->getQuery()->getSingleScalarResult();
        }
        if($page!=""){
            $query->setFirstResult(($page-1)*$limit);
        }
        if($limit && $limit!= ""){
            $query->setMaxResults($limit);
        }
        return  $query->getQuery()->getResult();
    }
}
