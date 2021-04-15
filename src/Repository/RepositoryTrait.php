<?php

namespace App\Repository;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\QueryBuilder;

/**
 * Trait RepositoryTrait
 * @package Symfony\Component\Security\Http\Util
 */
trait RepositoryTrait
{
    /**
     * @param array $criteria
     * @param int $offset
     * @param int $limit
     * @param string $orderBy
     * @param string $order
     * @return int|mixed|string
     * @throws \Doctrine\ORM\Query\QueryException
     */
    public function findByCriteria(
        array $criteria = [],
        int $offset= 0,
        int $limit=20,
        string $orderBy= "id",
        string $order = "asc" )
    {

        $columns = $this->getClassMetadata()->getFieldNames();
        $qb = $this->createQueryBuilder('e');
        $qb = $this->addCriteria($qb, $criteria);

        if ($offset != "") {
            $qb->setFirstResult($offset);
        }

        if ($limit != "") {
            $qb->setMaxResults($limit);
        }

        if (in_array($orderBy, $columns)) {
            if (in_array($order, ['asc', 'desc'])) {
                $qb->orderBy("e.$orderBy", $order);
            }
        }

        return $qb->getQuery()->getResult();
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
        $qb = $this->addCriteria($qb, $criteria);
        return (int)$qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @param string $operator
     * @param string $key
     * @param $value
     * @return Criteria
     */
    private function createCriteria(string $operator, string $key, $value) : Criteria
    {
        return Criteria::create()
            ->andWhere(Criteria::expr()->$operator($key, $value));
    }
    /**
     * @param QueryBuilder $queryBuilder
     * @param array $criteria
     * @return QueryBuilder
     * @throws \Doctrine\ORM\Query\QueryException
     */
    private function addCriteria(QueryBuilder $queryBuilder, array $criteria = []) : QueryBuilder
    {
        foreach ($criteria as $key => $value){
            if ('' != $value){
                if(\is_array($value)){
                    $i=1;
                    foreach ($value as $op => $v){
                        $queryBuilder = $this->andWhere($queryBuilder, $key, $op, $key.$i, $v);
                        $i++;
                    }
                } else {
                    $operator = 'contains';
                    $clause = $this->createCriteria($operator, $key, $value);
                    $queryBuilder->addCriteria($clause);
                }
            }
        }
        return $queryBuilder;
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param string $field
     * @param string $operator
     * @param string $parameter
     * @param string $value
     * @return QueryBuilder
     */
    private function andWhere(
        QueryBuilder $queryBuilder,
        string $field,
        string $operator,
        string $parameter,
        string $value
    ): QueryBuilder
    {
        $alias = $queryBuilder->getRootAliases()[0];
        switch ($operator){
            case 'eq':
                $queryBuilder->andWhere("$alias.$field = :$parameter")->setParameter($parameter, $value);
                break;
            case 'gt':
                $queryBuilder->andWhere("$alias.$field < :$parameter")->setParameter($parameter, $value);
                break;
            case 'lt':
                $queryBuilder->andWhere("$alias.$field > :$parameter")->setParameter($parameter, $value);
                break;
            case 'gte':
                $queryBuilder->andWhere("$alias.$field <= :$parameter")->setParameter($parameter, $value);
                break;
            case 'lte':
                $queryBuilder->andWhere("$alias.$field >= :$parameter")->setParameter($parameter, $value);
                break;
            case 'neq':
                $queryBuilder->andWhere("$alias.$field != :$parameter")->setParameter($parameter, $value);
                break;
            case 'contains':
                $queryBuilder->andWhere("$alias.$field LIKE :$parameter")->setParameter($parameter, "%$value%");
                break;
            case 'startsWith':
                $queryBuilder->andWhere("$alias.$field LIKE :$parameter")->setParameter($parameter, "$value%");
                break;
            case 'endsWith':
                $queryBuilder->andWhere("$alias.$field LIKE :$parameter")->setParameter($parameter, "%$value");
                break;
            default:
                throw new \RuntimeException('Unknown comparison operator: ' . $operator);
        }
        return $queryBuilder;
    }

    /**
     * @return string[]
     */
    public static function getOperators(): array
    {
        return ['eq', 'gt', 'lt', 'gte', 'lte', 'neq', 'contains', 'startsWith', 'endsWith'];
    }
}
