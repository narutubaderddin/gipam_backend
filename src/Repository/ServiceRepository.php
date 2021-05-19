<?php

namespace App\Repository;

use App\Entity\Service;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use FOS\RestBundle\Request\ParamFetcherInterface;

/**
 * @method Service|null find($id, $lockMode = null, $lockVersion = null)
 * @method Service|null findOneBy(array $criteria, array $orderBy = null)
 * @method Service[]    findAll()
 * @method Service[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServiceRepository extends ServiceEntityRepository
{
    use RepositoryTrait;

    public const SEARCH_FIELDS = ['label_param' => 'label', 'acronym_param' => 'acronym', 'subDivision_label_param' => 'subDivision_label'];

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Service::class);
    }
    public function findRecordsByEntityNameAndCriteria(ParamFetcherInterface $paramFetcher,$count,$page=1,$limit=0){
//        $ministry =$paramFetcher->get('ministries')??"";
        $establishment =$paramFetcher->get('establishments')??"";
        $subDivision=$paramFetcher->get('subDivisions')??"";
        $query = $this->createQueryBuilder('service')
            ->leftJoin('service.subDivision','subDivision')
            ->leftJoin('subDivision.establishment','establishment');

        if($subDivision != ""){
            $subDivision = json_decode($subDivision, true);
            if(!is_array($subDivision)){
                throw  new \RuntimeException('subDivision value should be an array');
            }
            if(count($subDivision)>0){
                $query->andWhere('subDivision.id in (:subDivision)')->setParameter('subDivision',$subDivision);
            }
        }
        if($establishment !=""){
            $establishment = json_decode($establishment, true);
            if(!is_array($establishment)){
                throw  new \RuntimeException('establishment value should be an array');
            }
            if(count($establishment)>0){
                $query->andWhere('establishment.id in (:establishments)')->setParameter('establishments',$establishment);
            }
        }
        if($count){
            $query->select('count(subDivision.id)');
            return $query->getQuery()->getSingleScalarResult();
        }
        if($page!=""){
            $query->setFirstResult(($page - 1) * $limit);
        }
        if($limit && $limit!=""){
            $query->setMaxResults($limit);
        }
        return  $query->getQuery()->getResult();
    }
}
