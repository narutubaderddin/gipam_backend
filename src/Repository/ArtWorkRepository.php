<?php

namespace App\Repository;

use App\Entity\ArtWork;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ArtWork|null find($id, $lockMode = null, $lockVersion = null)
 * @method ArtWork|null findOneBy(array $criteria, array $orderBy = null)
 * @method ArtWork[]    findAll()
 * @method ArtWork[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArtWorkRepository extends ServiceEntityRepository
{
    use RepositoryTrait;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ArtWork::class);
    }

    public function searchByCriteria(
        array $criteria = [],
        int $offset= 0,
        int $limit=20,
        string $orderBy= "id",
        string $order = "asc")
    {


        $columns = $this->getClassMetadata()->getFieldNames();
        $qb = $this->createQueryBuilder('e');
        $qb = $this->addSearchCriteria($criteria, $qb);

        if ($offset != "") {
            $qb->setFirstResult($offset);
        }

        if ($limit != "") {
            $qb->setMaxResults($limit);
        }

        if (in_array($order, ['asc', 'desc'])) {
            $qb->orderBy("e.$orderBy", $order);
        }

        return $qb->getQuery()->getResult();
    }


    /**
     * @return QueryBuilder
     */
    public function searchByMode(QueryBuilder $qb, $mode): QueryBuilder
    {
        [$operation,$operationLength,$operationWidth] = $mode == "Portait" ? [">=","IS NOT","IS"] : ["<=","IS","IS NOT"];
        $qb->andWhere("((e.length $operation e.width) or (e.length $operationLength NULL and e.width $operationWidth NULL ))
         or 
         (((e.length IS NULL and e.width IS NULL) and (e.totalLength $operation e.totalWidth)) or (e.totalLength $operationLength NULL and e.totalWidth $operationWidth NULL ))");
        return $qb;
    }
    /**
     * @param QueryBuilder $qb
     * @param array $criteria
     * @return QueryBuilder
     */
    public function searchByOrCriteria(QueryBuilder $qb, array $criteria,$search): QueryBuilder
    {
        $orX = $qb->expr()->orX();
        $existCriteria = false;
        $criteriasAttr = ['title'];
        foreach ($criteriasAttr as $crt){
            $orX->add($qb->expr()->like("lower(e.$crt)", $qb->expr()->literal('%'.strtolower($search).'%')));
        }
        if($existCriteria){
            $qb->andWhere($orX);
        }
        $qb->andWhere($orX);
        return $qb;
    }
    public function searchByOrArray(QueryBuilder $qb,$criteria): QueryBuilder
    {
        $orX = $qb->expr()->orX();

        if(isset($criteria['field']) && !empty($criteria['field'])) {
            $search = $criteria['field'];
            unset($criteria['field']);
            foreach ($search as $key => $value) {
                $orX->add($qb->expr()->in("e.field",json_decode($value)));
            }
        }
        if(isset($criteria['denomination']) && !empty($criteria['denomination'])) {
            $search = $criteria['denomination'];
            unset($criteria['denomination']);
            foreach ($search as $key => $value) {
                $orX->add($qb->expr()->in("e.denomination",json_decode($value)));
            }
        }
        $qb->andWhere($orX);
        return $qb;
    }

    /**
     * @param array $criteria
     * @return int
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\Query\QueryException
     */
    public function countByCriteria(array $criteria = []) : int
    {
        $qb = $this->createQueryBuilder('e');
        $qb->select('count(e.id)');
        $qb = $this->addSearchCriteria($criteria, $qb);
        return (int)$qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @param array $criteria
     * @param QueryBuilder $qb
     * @return QueryBuilder
     * @throws \Doctrine\ORM\Query\QueryException
     */
    public function addSearchCriteria(array $criteria, QueryBuilder $qb): QueryBuilder
    {
        if (isset($criteria['search']) && !empty($criteria['search'])) {
            $search = $criteria['search'];
            unset($criteria['search']);
            foreach ($search as $key => $value) {
                $qb = $this->searchByOrCriteria($qb, $criteria, $value);
            }

        }

        if (isset($criteria['mode']) && !empty($criteria['mode'])) {
            $mode = $criteria['mode'];
            unset($criteria['mode']);
            foreach ($mode as $key => $value) {
                $qb = $this->searchByMode($qb, $value);
            }
        }
        if((isset($criteria['field']) && !empty($criteria['field']))||(isset($criteria['denomination']) && !empty($criteria['denomination']))){
            $qb = $this->searchByOrArray($qb, $criteria);
            unset($criteria['field']);
            unset($criteria['denomination']);
        }
        $qb = $this->addCriteria($qb, $criteria);
        return $qb;
    }
}
