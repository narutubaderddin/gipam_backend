<?php

namespace App\Repository;

use App\Entity\Denomination;
use App\Entity\MaterialTechnique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
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

    public function findOneByLabelAndDenomination(string $label, Denomination $denomination)
    {
        $query = $this->createQueryBuilder('mt')
            ->leftJoin('mt.denominations', 'denominations')
            ->where('mt.label = :label')
            ->andWhere('denominations.id = :denominationId')
            ->setParameters(['label' => $label, 'denominationId' => $denomination->getId()])
            ->getQuery();

        return $query->getOneOrNullResult();
    }

    /**
     * @param $page
     * @param $limit
     * @param $field
     * @param $denomination
     * @param bool $count
     * @return int|mixed|string
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function findByFieldAndDenomination($page,$limit,$field,$denomination,$count=false){
        $query = $this->createQueryBuilder('materialTechnique')
                      ->leftJoin('materialTechnique.denominations','denominations')
                      ->leftJoin('denominations.field','field')
                       ->where('materialTechnique.active = true') ;
        if($field!=""){
            eval("\$field = $field;");
            if(!is_array($field)){
                throw  new \RuntimeException('field value should be an array');
            }
            if(count($field)>0){
                $query->andWhere('field.id in (:fields)')->setParameter('fields',$field);
            }
        }

        if($denomination!=""){
            eval("\$denomination = $denomination;");
            if(!is_array($denomination)){
                throw  new \RuntimeException('denomination value should be an array');
            }
            if(count($denomination)>0){
                $query->andWhere('denominations.id in (:denomination)')->setParameter('denomination',$denomination);
            }
        }
        if($count){
            $query->select('count(materialTechnique.id)');
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
