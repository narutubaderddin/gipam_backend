<?php

namespace App\Repository;

use App\Entity\SubDivision;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SubDivision|null find($id, $lockMode = null, $lockVersion = null)
 * @method SubDivision|null findOneBy(array $criteria, array $orderBy = null)
 * @method SubDivision[]    findAll()
 * @method SubDivision[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubDivisionRepository extends ServiceEntityRepository
{
    use RepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SubDivision::class);
    }
    public function findSubDivisionByCriteria($page, $limit, $ministry, $establishment,$count=false){
        $query = $this->createQueryBuilder('sub_division')
                       ->leftJoin('sub_division.establishment','establishment')
                       ->leftJoin('establishment.ministry','ministry');
        if($ministry != ""){
            eval("\$ministry = $ministry;");
            if(!is_array($ministry)){
                throw  new \RuntimeException('ministry value should be an array');
            }
            if(count($ministry)>0){
                $query->andWhere('ministry.id in (:ministries)')->setParameter('ministries',$ministry);
            }
        }
        if($establishment !=""){
            eval("\$establishment = $establishment;");
            if(!is_array($establishment)){
                throw  new \RuntimeException('establishment value should be an array');
            }
            if(count($establishment)>0){
                $query->andWhere('establishment.id in (:establishments)')->setParameter('establishments',$establishment);
            }
        }
        if($count){
            $query->select('count(sub_division.id)');
            return $query->getQuery()->getSingleScalarResult();
        }
        if($page!=""){
            $query->setFirstResult(($page*$limit)+1);
        }
        if($limit && $limit!=""){
            $query->setMaxResults($limit);
        }
        return  $query->getQuery()->getResult();
    }
}
