<?php

namespace App\Repository;

use App\Entity\Denomination;
use App\Entity\MaterialTechnique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;
use FOS\RestBundle\Request\ParamFetcherInterface;

/**
 * @method MaterialTechnique|null find($id, $lockMode = null, $lockVersion = null)
 * @method MaterialTechnique|null findOneBy(array $criteria, array $orderBy = null)
 * @method MaterialTechnique[]    findAll()
 * @method MaterialTechnique[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MaterialTechniqueRepository extends ServiceEntityRepository
{
    use RepositoryTrait;

    public const SEARCH_FIELDS = ['label_param' => 'label'];

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
     * @param ParamFetcherInterface $paramFetcher
     * @param bool $count
     * @param int $page
     * @param int $limit
     * @return int|mixed|string
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function findRecordsByEntityNameAndCriteria(ParamFetcherInterface $paramFetcher,$count,$page=1,$limit=0){
        $field =$paramFetcher->get('fields')??"";
        $denomination =$paramFetcher->get('denominations')??"";
        $query = $this->createQueryBuilder('materialTechnique')
                      ->leftJoin('materialTechnique.denominations','denominations')
                      ->leftJoin('denominations.field','field')
                       ->where('materialTechnique.active = true') ;
        if($field!=""){
            $field = json_decode($field, true);
            if(!is_array($field)){
                throw  new \RuntimeException('field value should be an array');
            }
            if(count($field)>0){
                $query->andWhere('field.id in (:fields)')->setParameter('fields',$field);
            }
        }

        if($denomination!=""){
            $denomination = json_decode($denomination, true);
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
            $query->setFirstResult(($page - 1) * $limit);
        }
        if($limit && $limit!= ""){
            $query->setMaxResults($limit);
        }
        return  $query->getQuery()->getResult();
    }
}
