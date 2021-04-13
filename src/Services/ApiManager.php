<?php

namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Request\ParamFetcherInterface;

/**
 * Class ApiManager
 * @package App\Services
 */
class ApiManager
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * ApiManager constructor.
     * @param EntityManagerInterface $em.
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param $entity
     * @param bool $doFlush
     * @return mixed
     */
    public function save($entity, bool $doFlush = true)
    {
        $this->em->persist($entity);
        if ($doFlush) {
            $this->em->flush();
        }

        return $entity;
    }

    /**
     * @param $entity
     * @param bool $doFlush
     * @return mixed
     */
    public function delete($entity, bool $doFlush = true)
    {
        $this->em->remove($entity);
        if ($doFlush) {
            $this->em->flush();
        }

        return $entity;
    }

    /**
     * @param string $fqcn
     * @param ParamFetcherInterface $paramFetcher
     * @return array
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findRecordsByEntityName(string $fqcn, ParamFetcherInterface $paramFetcher): array
    {
        $offset = $paramFetcher->get('offset');
        $limit = $paramFetcher->get('limit');
        $sortBy = $paramFetcher->get('sort_by')?? 'id';
        $sort = $paramFetcher->get('sort')?? 'asc';
        $columns = $this->em->getClassMetadata($fqcn)->getFieldNames();
        $data = [];

        $queryBuilder = $this->em->createQueryBuilder();
        $data['total_results'] =(int) $queryBuilder->select('COUNT(DISTINCT p.id)')
            ->from($fqcn, 'p')
            ->getQuery()
            ->getSingleScalarResult();

        $qb = $this->em->createQueryBuilder();
        $qb->select('p')
            ->from($fqcn, 'p');

        if ($offset != "") {
            $qb->setFirstResult($offset);
        }

        if ($limit != "") {
            $qb->setMaxResults($limit);
        }

        if (in_array($sortBy, $columns)) {
            if (in_array($sort, ['asc', 'desc'])) {
                $qb->orderBy("p.$sortBy", $sort);
            }
        }
        $data['results'] = $qb->getQuery()->getResult();

        return $data;
    }
}