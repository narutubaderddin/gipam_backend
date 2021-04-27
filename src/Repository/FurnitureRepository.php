<?php

namespace App\Repository;

use App\Entity\ArtWork;
use App\Entity\Furniture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Furniture|null find($id, $lockMode = null, $lockVersion = null)
 * @method Furniture|null findOneBy(array $criteria, array $orderBy = null)
 * @method Furniture[]    findAll()
 * @method Furniture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FurnitureRepository extends ServiceEntityRepository
{
    use RepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Furniture::class);
    }

    public static $columns = [
        'id' => ['field' => 'id', 'operator' => 'equal', 'entity' => 'artWork'],
        'titre' => ['field' => 'title', 'operator' => 'like', 'entity' => 'artWork'],
        'domaine' => ['field' => 'id', 'operator' => 'in', 'entity' => 'field'],
        'auteurs' => ['field' => 'id', 'operator' => 'in', 'entity' => 'authors'],
        'denomination' => ['field' => 'id', 'operator' => 'in', 'entity' => 'denomination'],
        'materialTechnique' => ['field' => 'id', 'operator' => 'in', 'entity' => 'materialTechnique'],
        'style' => ['field' => 'id', 'operator' => 'in', 'entity' => 'style'],
    ];

    public function getArtWorkList(array $filter, array $advancedFilter, array $headerFilters,$page,$limit)
    {
        $query = $this->createQueryBuilder('artWork');
        $query->where($query->expr()->isInstanceOf('artWork', ArtWork::class));
        $query->leftJoin('artWork.field', 'field')
            ->leftJoin('artWork.denomination', 'denomination')
            ->leftJoin('artWork.materialTechnique', 'materialTechnique')
            ->leftJoin('artWork.authors', 'authors')
            ->leftJoin('artWork.style', 'style');
        foreach ($filter as $key => $value) {
            if (array_key_exists($key, self::$columns)) {
                if ($key == 'id' && is_array($filter['id'])) {
                    $query = $this->addColumnFilter($query, $filter, $headerFilters, $key, self::$columns[$key]['field'], 'range', self::$columns[$key]['entity']);
                } else {
                    $query = $this->addColumnFilter($query, $filter, $headerFilters, $key, self::$columns[$key]['field'], self::$columns[$key]['operator'], self::$columns[$key]['entity']);
                }
            }
        }
        $query = $this->addAdvancedFilter($query, $advancedFilter, $headerFilters);
        $query->setFirstResult(($page*$limit)+1)->setMaxResults($limit);
        return $query->getQuery()->getResult();
    }

    public function addColumnFilter(QueryBuilder $query, array $filter, array $headerFilters, string $column, string $queryKey, string $type, string $entity)
    {
        if (array_key_exists($column, $filter) && isset($filter[$column]) && $filter[$column] != "") {
            switch ($type) {
                case 'like':
                    $dql = $entity . '.' . $queryKey . ' like :' . $column;

                    if (array_key_exists($column, $headerFilters) && isset($headerFilters[$column]) && $headerFilters[$column] != "") {
                        $dql .= ' and ' . $entity . '.' . $queryKey . ' like :header' . $column;
                    }
                    $query->andWhere('(' . $dql . ')')
                        ->setParameter($column, '%' . $filter[$column] . '%');

                    if (array_key_exists($column, $headerFilters) && isset($headerFilters[$column]) && $headerFilters[$column] != "") {
                        $query->setParameter('header' . $column, $headerFilters[$column]);
                    }
                    break;
                case 'range':
                    $query->andWhere($entity . '.' . $queryKey . ' between :data1 and :data2');
                    $query->setParameter('data1', $filter[$column][0])
                        ->setParameter('data2', $filter[$column][1]);
                    break;
                case 'equal':
                    $query->andWhere($entity . '.' . $queryKey . ' = :' . $column)
                        ->setParameter($column, $filter[$column]);
                    break;
                case 'in':
                    if (count($filter[$column]) > 0) {
                        $query->andWhere($entity . '.' . $queryKey . ' in (:' . $column . ')')
                            ->setParameter($column, $filter[$column]);
                    }

                    break;
            }

        }

        return $query;
    }

    /**
     * @param QueryBuilder $query
     * @param array $advancedFilter
     * @param array $headerFilter
     * @return QueryBuilder
     */
    private function addAdvancedFilter(QueryBuilder $query, array $advancedFilter, array $headerFilter)
    {
        $dqlString = "( ";
        $hasAdvancedFilter = false;
        $i=0;
        foreach ($advancedFilter as $key => $value) {
            $subDqlString = "";
            if (array_key_exists($key, self::$columns) && isset($advancedFilter[$key]) && $advancedFilter[$key] != "") {
                $keyData = self::$columns[$key];
                switch ($keyData['operator']) {
                    case'in':
                        $oprator = $value['operator'] == 'not' ? ' not in' : ' in';
                        $subDqlString = $keyData['entity'] . '.' . $keyData['field'] . $oprator . ' (:' . $key . ')';
                        break;
                }
                $i++;
                $dqlString .= "( " . $subDqlString . " )";
                if($i<count($advancedFilter)){
                    $operator = $value['operator'] == 'or' ? 'or' : 'and';
                    $dqlString.= $operator;
                }
                $hasAdvancedFilter = true;
            }

        }
        $dqlString .= ' )';
        if (!$hasAdvancedFilter) {
            return $query;
        }
        $query->andWhere($dqlString);
        foreach ($advancedFilter as $key => $value) {
            $keyData = self::$columns[$key];
            switch ($keyData['operator']){
                case 'in':
                    $query->setParameter($key,$value['value']);
                    break;
            }
        }
        return $query;
    }


}
