<?php

namespace App\Repository;

use App\Entity\Department;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use FOS\RestBundle\Request\ParamFetcherInterface;

/**
 * @method Department|null find($id, $lockMode = null, $lockVersion = null)
 * @method Department|null findOneBy(array $criteria, array $orderBy = null)
 * @method Department[]    findAll()
 * @method Department[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DepartmentRepository extends ServiceEntityRepository
{
    use RepositoryTrait;

    public const SEARCH_FIELDS = [
        'name_param' => 'name',
        "region_name_param" => "region_name"
    ];

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Department::class);
    }
    public function findRecordsByEntityNameAndCriteria(ParamFetcherInterface $paramFetcher,$count,$page=1,$limit=0){
        $region =$paramFetcher->get('region')??"";
        $query = $this->createQueryBuilder('department')
                ->leftJoin('department.region','region');
        $query = $this->addArrayCriteriaCondition($query, $region, 'region');

        if($count){
            $query->select('count(department.id)');
            return $query->getQuery()->getSingleScalarResult();
        }
        if($page!=""){
            $query->setFirstResult(($page*$limit)+1);
        }
        if($limit && $limit!= ""){
            $query->setMaxResults($limit);
        }
        return  $query->getQuery()->getResult();
    }
}
