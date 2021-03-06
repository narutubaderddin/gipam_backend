<?php

namespace App\Repository;

use Doctrine\Common\Collections\Criteria;
use Doctrine\DBAL\Connection;
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
     * @param string|null $search
     * @return int|mixed|string
     * @throws \Doctrine\ORM\Query\QueryException
     */
    public function findByCriteria(
        array $criteria = [],
        int $offset = 0,
        int $limit = 0,
        string $orderBy = "id",
        string $order = "asc",
        string $search = null
    )
    {
        $columns = $this->getClassMetadata()->getFieldNames();

        if (defined(get_class() . '::SEARCH_FIELDS')) {
            $columns = array_merge($columns, self::SEARCH_FIELDS);
        }
        $qb = $this->createQueryBuilder('e');
        $this->leftJoins($qb, $criteria);
        $qb = $this->addCriteria($qb, $criteria);
        if ($search) {
            $or = $qb->expr()->orX();
            foreach (self::SEARCH_FIELDS as $key => $value) {
                $field = $this->getField($qb, $value);
                $or->add("LOWER($field) LIKE  :$key");
            }
            $qb->andWhere($or);
            foreach (self::SEARCH_FIELDS as $key => $value) {
                $qb->setParameter("$key", '%' . strtolower($search) . '%');
            }
        }

        if ($offset != "") {
            $qb->setFirstResult($offset);
        }

        if ($limit && $limit != "") {
            $qb->setMaxResults($limit);
        }

        if (in_array($orderBy, $columns) && in_array($order, ['asc', 'desc'])) {
            $field =  $this->getField($qb, $orderBy);
            $qb->orderBy($field, $order);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * @param array $criteria
     * @param string|null $search
     * @return int
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\Query\QueryException
     */
    public function countByCriteria(array $criteria = [], string $search = null): int
    {
        $qb = $this->createQueryBuilder('e');
        $qb->select('count(e.id)');
        $this->leftJoins($qb, $criteria);
        $qb = $this->addCriteria($qb, $criteria);
        if ($search) {
            $or = $qb->expr()->orX();
            foreach (self::SEARCH_FIELDS as $key => $value) {
                $field = $this->getField($qb, $value);
                $or->add("LOWER($field) LIKE  :$key");
            }
            $qb->andWhere($or);
            foreach (self::SEARCH_FIELDS as $key => $value) {
                $qb->setParameter("$key", '%' . strtolower($search) . '%');
            }
        }
        return (int)$qb->getQuery()->getSingleScalarResult();
    }

    public function getField(QueryBuilder $queryBuilder, $field): string
    {
        $alias = $queryBuilder->getRootAliases()[0];
        $dql = $queryBuilder->getDQL();
        if (strpos($field, '_')) {
            $ret = explode('_', $field);
            $relatedEntity = $ret[0];
            $relatedEntityField = $ret[1];
            if (!stripos( $dql, "left join $alias.$relatedEntity $relatedEntity")) {
                $queryBuilder->leftJoin("$alias.$relatedEntity", $relatedEntity);
            }
            return "$relatedEntity.$relatedEntityField";
        }
        return  "$alias.$field";
    }

    public function leftJoins(QueryBuilder $queryBuilder, array &$criteria = [])
    {
        $alias = $queryBuilder->getRootAliases()[0];
        $joined = [];
        $dql = $queryBuilder->getDQL();
        foreach ($criteria as $key => $value) {
            if (strpos($key, '_') && $value !== '') {
                $ret = explode('_', $key);
                $relatedEntity = $ret[0];
                $relatedEntityField = $ret[1];
                if (!in_array($relatedEntity, $joined) && !stripos( $dql, "left join $alias.$relatedEntity $relatedEntity")) {
                    $queryBuilder->leftJoin("$alias.$relatedEntity", $relatedEntity);
                    $joined[] = $relatedEntity;
                }
                $criteria["$relatedEntity.$relatedEntityField"] = $criteria[$key];
                unset($criteria[$key]);
            }
        }
    }

    /**
     * @param string $operator
     * @param string $key
     * @param $value
     * @return Criteria
     */
    private function createCriteria(string $operator, string $key, $value): Criteria
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
    private function addCriteria(QueryBuilder $queryBuilder, array $criteria = []): QueryBuilder
    {
        foreach ($criteria as $key => $value) {
            if ('' != $value) {
                if (\is_array($value)) {
                    $i = 1;
                    foreach ($value as $op => $v) {
                        $queryBuilder = $this->andWhere($queryBuilder, $key, $op, $key . $i, $v);
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
        // here field could be with a join entityName.field so we omit adding alias
        if (!strpos( $field, '.')) {
            $field = "$alias.$field";
        } else {
            $parameter = str_replace('.', '', $parameter);
        }
        switch ($operator) {
            case 'eq':
                $queryBuilder->andWhere("$field = :$parameter")->setParameter($parameter, $value);
                break;
            case 'lt':
                $queryBuilder->andWhere("$field < :$parameter")->setParameter($parameter, $value);
                break;
            case 'gt':
                $queryBuilder->andWhere("$field > :$parameter")->setParameter($parameter, $value);
                break;
            case 'lte':
                $queryBuilder->andWhere("$field <= :$parameter")->setParameter($parameter, $value);
                break;
            case 'gte':
                $queryBuilder->andWhere("$field >= :$parameter")->setParameter($parameter, $value);
                break;
            case 'neq':
                $queryBuilder->andWhere("$field != :$parameter")->setParameter($parameter, $value);
                break;
            case 'contains':
                $queryBuilder->andWhere("LOWER($field) LIKE :$parameter")->setParameter($parameter,
                    '%' . strtolower($value) . '%');
                break;
            case 'startsWith':
                $queryBuilder->andWhere("LOWER($field) LIKE :$parameter")->setParameter($parameter,
                    strtolower($value) . '%');
                break;
            case 'in':
                $value = json_decode($value, true);
                if (!is_array($value)) {
                    throw new \RuntimeException('value should be an array');
                }
                $queryBuilder->andWhere("$field IN (:$parameter)")->setParameter($parameter, $value, Connection::PARAM_STR_ARRAY);
                break;
            case 'endsWith':
                $queryBuilder->andWhere("LOWER($field) LIKE :$parameter")->setParameter($parameter,
                    '%' . strtolower($value));
                break;
            case 'gtOrNull':
                $queryBuilder->andWhere("$field > :$parameter OR $field IS NULL")->setParameter($parameter, $value);
                break;
            case 'equalDate':
                $date = new \DateTime($value);
                $queryBuilder->andWhere($queryBuilder->expr()->between("$field", ':date_start', ':date_end'))
                ->setParameter('date_start', $date->format('Y-m-d 00:00:00'))
                ->setParameter('date_end',   $date->format('Y-m-d 23:59:59'));
                break;
            default:
                throw new \RuntimeException('Unknown comparison operator: ' . $operator);
        }
        return $queryBuilder;
    }

    public function iFindBy(array $criteria)
    {
        $queryBuilder = $this->createQueryBuilder('e');
        $columns = $this->getClassMetadata()->getFieldNames();
        foreach ($criteria as $key => $value) {
            if (in_array($key, $columns)) {
                $queryBuilder->andWhere("LOWER(e.$key) = :param_$key")->setParameter("param_$key", strtolower($value));
            }
        }
        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @return string[]
     */
    public static function getOperators(): array
    {
        return ['eq', 'gt', 'lt', 'gte', 'lte', 'neq', 'contains', 'startsWith', 'endsWith','in', 'gtOrNull'];
    }

    public function findRecordsByEntityNameAndCriteria($count, $page = 1, $limit = 0)
    {
        if ($count) {
            return $this->count([]);
        }
        $query = $this->createQueryBuilder('repositoryTrait');
        if ($page != "") {
            $query->setFirstResult(($page * $limit) + 1);
        }
        if ($limit && $limit != "") {
            $query->setMaxResults($limit);
        }
        return $query->getQuery()->getResult();

    }

    private function addArrayCriteriaCondition(QueryBuilder $query, $data, $key)
    {

        $data = is_string($data) ? json_decode($data, true) : false;
        if ($data && !is_array($data)) {
            throw new \RuntimeException("$key value should be an array");

        }
        if ($data && count($data) > 0) {
            $query->andWhere($key . ".id in (:" . $key . ")")->setParameter($key, $data);
        }
        return $query;
    }
}
