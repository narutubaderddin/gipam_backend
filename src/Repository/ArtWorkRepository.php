<?php

namespace App\Repository;

use App\Entity\ArtWork;
use App\Entity\DepositStatus;
use App\Entity\Movement;
use App\Entity\PropertyStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Self_;
use function PHPUnit\Framework\isNan;

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

    private static $entityInstance = [
        'depot' => DepositStatus::class,
        'propriete' => PropertyStatus::class
    ];
    public static $columns = [
        'id' => ['field' => 'id', 'operator' => 'equal', 'entity' => 'artWork'],
        'titre' => ['field' => 'title', 'operator' => 'like', 'entity' => 'artWork'],
        'domaine' => ['field' => 'id', 'operator' => 'in', 'entity' => 'field'],
        'unitNumber' => ['field' => 'numberOfUnit', 'operator' => 'equal', 'entity' => 'artWork'],
        'auteurs' => ['field' => 'id', 'operator' => 'in', 'entity' => 'authors'],
        'denomination' => ['field' => 'id', 'operator' => 'in', 'entity' => 'denomination'],
        'materialTechnique' => ['field' => 'id', 'operator' => 'in', 'entity' => 'materialTechnique'],
        'era' => ['field' => 'id', 'operator' => 'in', 'entity' => 'era'],
        'style' => ['field' => 'id', 'operator' => 'in', 'entity' => 'style'],
        'mouvement' => ['field' => 'id', 'operator' => 'in', 'entity' => 'movementType'],
        'mouvementAction' => ['field' => 'id', 'operator' => 'in', 'entity' => 'movementActionTypes'],
        'constat' => ['field' => 'id', 'operator' => 'in', 'entity' => 'reportType'],
        'constatAction' => ['field' => 'id', 'operator' => 'in', 'entity' => 'reportActionType'],
        'length' => ['field' => 'length', 'operator' => 'range', 'entity' => 'artWork'],
        'lengthTotal' => ['field' => 'totalLength', 'operator' => 'range', 'entity' => 'artWork'],
        'width' => ['field' => 'width', 'operator' => 'range', 'entity' => 'artWork'],
        'widthTotal' => ['field' => 'totalWidth', 'operator' => 'range', 'entity' => 'artWork'],
        'height' => ['field' => 'height', 'operator' => 'range', 'entity' => 'artWork'],
        'heightTotal' => ['field' => 'totalHeight', 'operator' => 'range', 'entity' => 'artWork'],
        'depth' => ['field' => 'depth', 'operator' => 'range', 'entity' => 'artWork'],
        'weight' => ['field' => 'weight', 'operator' => 'range', 'entity' => 'artWork'],
        'status' => ['field' => 'status', 'operator' => 'instance', 'entity' => 'status'],
        'deposant' => ['field' => 'id', 'operator' => 'in', 'entity' => 'depositor'],
        'categorie' => ['field' => 'id', 'operator' => 'in', 'entity' => 'category'],
        'entryMode' => ['field' => 'id', 'operator' => 'in', 'entity' => 'entryMode'],
        'arretNumber' => ['field' => 'stopNumber', 'operator' => 'equal', 'entity' => 'depositStatus'],
        'region' => ['field' => 'id', 'operator' => 'in', 'entity' => 'region'],
        'departement' => ['field' => 'id', 'operator' => 'in', 'entity' => 'department'],
        'communes' => ['field' => 'id', 'operator' => 'in', 'entity' => 'commune'],
        'batiment' => ['field' => 'id', 'operator' => 'in', 'entity' => 'building'],
        'sites' => ['field' => 'id', 'operator' => 'in', 'entity' => 'site'],
        'localType' => ['field' => 'id', 'operator' => 'in', 'entity' => 'location_type'],
        'creationDate' => ['field' => 'createdAt', 'operator' => 'equalDate', 'entity' => 'artWork'],
        'correspondant' => ['field' => 'id', 'operator' => 'equalDate', 'entity' => 'correspondents'],
        'responsible' => ['field' => 'id', 'operator' => 'in', 'entity' => 'responsibles'],
        'establishementType' => ['field' => 'id', 'operator' => 'in', 'entity' => 'establishement_type'],
        'entryDate' => ['field' => 'depositDate', 'operator' => 'range', 'entity' => 'depositStatus'],
        'depotDate' => ['field' => 'entryDate', 'operator' => 'range', 'entity' => 'propertyStatus'],
    ];

    public static $tableColumns = [
        'id' => ['field' => 'id',  'entity' => 'artWork','operator'=>'in','search'=>false] ,
        'titre' => ['field' => 'title',  'entity' => 'artWork','operator'=>'like','search'=>true],
        'creationDate' => ['field' => 'createdAt',  'entity' => 'artWork','operator'=>'like','search'=>false],
        'field' => ['field' => 'label',  'entity' => 'field','operator'=>'like','search'=>true],
        'denomination' => ['field' => 'label',  'entity' => 'denomination','operator'=>'like','search'=>true],
        'materialTechnique' => ['field' => 'label',  'entity' => 'materialTechnique','operator'=>'like','search'=>true],
        'style' => ['field' => 'label',  'entity' => 'style','operator'=>'like','search'=>true],
        'authors' => ['field' => 'firstName',  'entity' => 'authors','operator'=>'like','search'=>true],
        'era' => ['field' => 'label',  'entity' => 'era','operator'=>'like','search'=>true],
        'depositor' => ['field' => 'name',  'entity' => 'depositor','operator'=>'like','search'=>true],
        'communes' => ['field' => 'name',  'entity' => 'commune','operator'=>'like','search'=>true],
        'buildings' => ['field' => 'name',  'entity' => 'building','operator'=>'like','search'=>true]
    ];

    /**
     * @param array $filter
     * @param array $advancedFilter
     * @param array $headerFilters
     * @param $searchQuery
     * @param $globalSearchQuery
     * @param $page
     * @param $limit
     * @param string $sortBy
     * @param string $sort
     * @param bool $count
     * @return int|mixed|string
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function getArtWorkList(array $filter, array $advancedFilter, array $headerFilters,$searchQuery,$globalSearchQuery, $page, $limit, $sortBy = 'id', $sort = 'desc', $count = false,$countTotal=false)
    {
        $query = $this->createArtWorkListQuery($filter, $advancedFilter, $headerFilters, $searchQuery, $globalSearchQuery);
        if ($count) {
            $query->select('count(artWork.id)');
            return $query->getQuery()->getSingleScalarResult();
        }
        $query->setFirstResult(($page - 1) * $limit)->setMaxResults($limit);
        if (array_key_exists($sortBy, self::$tableColumns)) {
            $sortDataKey = self::$tableColumns[$sortBy]['entity'] . '.' . self::$tableColumns[$sortBy]['field'];
            $query->orderBy($sortDataKey, $sort);
        }
        return $query->getQuery()->getResult();
    }

    public function getArtWorkListByOffset(array $filter, array $advancedFilter, array $headerFilters, $searchQuery, $globalSearchQuery, $offset, $limit, $sortBy = 'id', $sort = 'desc')
    {
        $query = $this->createArtWorkListQuery($filter, $advancedFilter, $headerFilters, $searchQuery, $globalSearchQuery);
        $query->setFirstResult($offset)->setMaxResults($limit);
        if (array_key_exists($sortBy, self::$tableColumns)) {
            $sortDataKey = self::$tableColumns[$sortBy]['entity'] . '.' . self::$tableColumns[$sortBy]['field'];
            $query->orderBy($sortDataKey, $sort);
        }
        return $query->getQuery()->getResult();
    }

    private function createArtWorkListQuery(array $filter, array $advancedFilter, array $headerFilters, $searchQuery, $globalSearchQuery)
    {
        $query = $this->createQueryBuilder('artWork');
        $query->where($query->expr()->isInstanceOf('artWork', ArtWork::class));
        $query->innerJoin('artWork.field', 'field')
            ->innerJoin('artWork.denomination', 'denomination')
            ->leftJoin('artWork.materialTechnique', 'materialTechnique')
            ->leftJoin('artWork.authors', 'authors')
            ->leftJoin('artWork.era', 'era')
            ->leftJoin('artWork.style', 'style')
            ->leftJoin('artWork.movements', 'movements',\Doctrine\ORM\Query\Expr\Join::WITH,
                "movements.date =( select MAX(movements2.date) from Main:Movement movements2 where movements2.furniture = artWork) ")
            ->leftJoin('movements.correspondents', 'correspondents')
            ->leftJoin('correspondents.establishment', 'establishment')
            ->leftJoin('establishment.ministry', 'ministry')
            ->leftJoin('establishment.type', 'establishement_type')
            ->leftJoin('establishment.subDivisions', 'sub_divisions')
            ->leftJoin('sub_divisions.locations', 'locations')
            ->leftJoin('locations.type', 'location_type')
            ->leftJoin('locations.room', 'room')
            ->leftJoin('room.building', 'building')
            ->leftJoin('building.site', 'site')
            ->leftJoin('building.commune', 'commune')
            ->leftJoin('building.responsibles', 'responsibles')
            ->leftJoin('commune.department', 'department')
            ->leftJoin('department.region', 'region')
            ->leftJoin('movements.type', 'movementType')
            ->leftJoin('movementType.movementActionTypes', 'movementActionTypes')
            ->leftJoin('artWork.reports', 'reports')
            ->leftJoin('reports.reportSubType', 'reportSubType')
            ->leftJoin('reportSubType.reportType', 'reportType')
            ->leftJoin('reports.actions', 'reportAction')
            ->leftJoin('reportAction.type', 'reportActionType')
            ->leftJoin('artWork.status', 'status')
            ->leftJoin(DepositStatus::class, 'depositStatus', \Doctrine\ORM\Query\Expr\Join::WITH, 'depositStatus = status')
            ->leftJoin(PropertyStatus::class, 'propertyStatus', \Doctrine\ORM\Query\Expr\Join::WITH, 'propertyStatus = status')
            ->leftJoin('depositStatus.depositor', 'depositor')
            ->leftJoin('propertyStatus.category', 'category')
            ->leftJoin('propertyStatus.entryMode', 'entryMode')
            //                        ->leftJoin('sub_divisions.services','services')
        ;
        $query->andWhere('artWork.isCreated = true');
        foreach ($filter as $key => $value) {
            if (array_key_exists($key, self::$columns)) {
                if ($key == 'id' && is_array($filter['id'])) {
                    $query = $this->addColumnFilter($query, $filter, $headerFilters, $key, self::$columns[$key]['field'], 'range', self::$columns[$key]['entity']);
                } else {
                    $query = $this->addColumnFilter($query, $filter, $headerFilters, $key, self::$columns[$key]['field'], self::$columns[$key]['operator'], self::$columns[$key]['entity']);
                }
            }
        }
        if (strlen($globalSearchQuery) > 0) {
            $query = $this->addGlobalQueryFilter($query, $globalSearchQuery);
        }

        if (strlen($searchQuery) > 0) {
            $query = $this->addQueryFilter($query, $searchQuery);
        }
        return $this->addAdvancedFilter($query, $advancedFilter, $headerFilters);
    }

    public function addColumnFilter(QueryBuilder $query, array $filter, array $headerFilters, string $column, string $queryKey, string $type, string $entity)
    {
        $isColumnFilterExist = (array_key_exists($column, $filter) && isset($filter[$column]) && $filter[$column] != "");
        $isColumnHeaderFilterExist = (array_key_exists($column, $headerFilters) && isset($headerFilters[$column]) && $headerFilters[$column] != "");
        if ($isColumnFilterExist || $isColumnHeaderFilterExist) {
            switch ($type) {
                case 'like':
                    $subDql = "";
                    if ($isColumnFilterExist) {
                        $subDql .= 'LOWER(' . $entity . '.' . $queryKey . ') like :' . $column;
                        if ($isColumnHeaderFilterExist) {
                            $subDql .= " and ";
                        }
                    }
                    if ($isColumnHeaderFilterExist) {
                        $subDql .= 'LOWER(' . $entity . '.' . $queryKey . ') like :header' . $column;
                    }

                    $query->andWhere('(' . $subDql . ')');

                    if ($isColumnFilterExist) {
                        $query->setParameter($column, '%' . strtolower($filter[$column]) . '%');
                    }
                    if ($isColumnHeaderFilterExist) {
                        $query->setParameter('header' . $column, '%' . strtolower($headerFilters[$column]) . '%');
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
                    if (($isColumnFilterExist && count($filter[$column]) > 0) || ($isColumnHeaderFilterExist && count($headerFilters[$column]) > 0)) {
                        $data = [];
                        $data = ($isColumnFilterExist && is_array($filter[$column]) && count($filter[$column]) > 0) ? array_unique(array_merge($data, $filter[$column])) : $data;
                        $data = ($isColumnHeaderFilterExist && is_array($headerFilters[$column]) && count($headerFilters[$column]) > 0) ? array_unique(array_merge($data, $headerFilters[$column])) : $data;
                        $query->andWhere($entity . '.' . $queryKey . ' in (:' . $column . ')');
                        $query->setParameter($column, $data, Connection::PARAM_STR_ARRAY);
                    }
                    break;
                case 'instance':
                    $data = [];
                    $data = ($isColumnFilterExist && is_array($filter[$column]) && count($filter[$column]) > 0) ? array_unique(array_merge($data, $filter[$column])) : $data;
                    $data = ($isColumnHeaderFilterExist && is_array($headerFilters[$column]) && count($headerFilters[$column]) > 0) ? array_unique(array_merge($data, $headerFilters[$column])) : $data;
                    $subDql = "";
                    $i = 0;
                    foreach ($data as $entityData) {
                        if (array_key_exists($entityData, self::$entityInstance)) {
                            $subDql .= "($entity INSTANCE of :entity_$i)";
                            if ($entityData !== end($data)) {
                                $subDql .= ' or ';
                            }
                            $i++;
                        }

                    }
                    if (count($data) > 0 && strlen($subDql) > 0) {
                        $query->andWhere("($subDql)");
                        $i = 0;
                        foreach ($data as $entityData) {
                            if (array_key_exists($entityData, self::$entityInstance)) {
                                $query->setParameter("entity_$i", $this->getEntityManager()->getClassMetadata(self::$entityInstance[$entityData]));
                                $i++;
                            }

                        }
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
        $i = 0;
        $keysOperator = "";
        $operator = 'and';
        foreach ($advancedFilter as $key => $value) {
            $subDqlString = "";
            $isColumnAdvancedFilterExist = array_key_exists($key, self::$columns) && isset($advancedFilter[$key]) && $advancedFilter[$key]['value'] != "";
            $isColumnHeaderFilterExist = array_key_exists($key, self::$columns) && isset($headerFilter[$key]) && $headerFilter[$key] != "";

            if ($isColumnHeaderFilterExist || $isColumnAdvancedFilterExist) {
                $dqlOperator = "";
                $keyData = self::$columns[$key];
                switch ($keyData['operator']) {
                    case'in':
                        if (($isColumnAdvancedFilterExist && count($value['value']) > 0) || ($isColumnHeaderFilterExist && count($headerFilter[$key]) > 0)) {
                            $dqlOperator = (is_array($value) && $operator == 'not') ? ' not in' : ' in';
                            $subDqlString = $keyData['entity'] . '.' . $keyData['field'] . $dqlOperator . ' (:' . $key . ')';
                        }
                        break;
                    case 'equal':
                        $dqlOperator = (is_array($value) && $operator == 'not') ? ' !=' : ' =';
                        $subDqlString = $keyData['entity'] . '.' . $keyData['field'] . $dqlOperator . ' :' . $key . '';
                        break;
                    case 'range':
                    case 'equalDate':
                        if ($operator != 'not') {
                            $subDqlString .= $keyData['entity'] . '.' . $keyData['field'] . ' between :value1 and :value2';
                        } else {
                            $subDqlString .= $keyData['entity'] . '.' . $keyData['field'] . ' > :value1 and ' . $keyData['entity'] . '.' . $keyData['field'] . ' < :value2';
                        }
                        break;

                }
                $dqlString .= (strlen($subDqlString) > 0) ? $keysOperator . "( " . $subDqlString . " )" : "";
                if ($i < count($advancedFilter) && strlen($subDqlString) > 0) {
                    $keysOperator = (is_array($value) && $operator == 'or') ? 'or' : 'and';
                    $hasAdvancedFilter = true;
                }

            }
            $operator = $value['operator'];

            $i++;
        }
        $dqlString .= ' )';
        if (!$hasAdvancedFilter) {
            return $query;
        }
        $query->andWhere($dqlString);

        foreach ($advancedFilter as $key => $value) {
            $isColumnAdvancedFilterExist = array_key_exists($key, self::$columns) && isset($advancedFilter[$key]) && $advancedFilter[$key] ['value'] != "";
            $isColumnHeaderFilterExist = array_key_exists($key, self::$columns) && isset($headerFilter[$key]) && $headerFilter[$key] != "";
            if ($isColumnHeaderFilterExist || $isColumnAdvancedFilterExist) {
                $keyData = self::$columns[$key];
                switch ($keyData['operator']) {
                    case 'in':
                        $data = [];
                        $data = ($isColumnAdvancedFilterExist && is_array($value['value']) && count($value['value']) > 0) ? array_unique(array_merge($data, $value['value'])) : $data;
                        $data = ($isColumnHeaderFilterExist && is_array($headerFilter[$key]) && count($headerFilter[$key]) > 0) ? array_unique(array_merge($data, $headerFilter[$key])) : $data;
                        if (count($data) > 0) {
                            $query->setParameter($key, $data);
                        }
                        break;
                    case 'equal':
                        $query->setParameter($key, $value['value']);
                        break;
                    case 'range':
                        $query->setParameter('value1', $value['value'][0]);
                        $query->setParameter('value2', $value['value'][1]);
                        break;
                    case 'equalDate':
                        $startDate = new \DateTime();
                        $startDate->setDate($value['value'], 1, 1);
                        $startDate->setTime(0, 0, 0);
                        $endDate = new \DateTime();
                        $endDate->setDate($value['value'], 12, 31);
                        $endDate->setTime(23, 59, 59);
                        $query->setParameter('value1', $startDate);
                        $query->setParameter('value2', $endDate);
                        break;
                }
            }

        }
        return $query;
    }

    public function searchByCriteria(
        array $criteria = [],
        int $offset = 0,
        int $limit = 20,
        string $orderBy = "id",
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
        [$operation,$operationLength,$operationWidth] = $mode == "Portrait" ? [">=","IS NOT","IS"] : ["<=","IS","IS NOT"];
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
    public function searchByOrCriteria(QueryBuilder $qb, array $criteria, $search): QueryBuilder
    {
        $orX = $qb->expr()->orX();
        $existCriteria = false;
        $criteriasAttr = ['title'];
        foreach ($criteriasAttr as $crt) {
            $orX->add($qb->expr()->like("lower(e.$crt)", $qb->expr()->literal('%' . strtolower($search) . '%')));
        }
        if ($existCriteria) {
            $qb->andWhere($orX);
        }
        $qb->andWhere($orX);
        return $qb;
    }

    public function searchByOrArray(QueryBuilder $qb, $criteria): QueryBuilder
    {
        $orX = $qb->expr()->orX();

        if (isset($criteria['field']) && !empty($criteria['field'])) {
            $search = $criteria['field'];
            unset($criteria['field']);
            foreach ($search as $key => $value) {
                $orX->add($qb->expr()->in("e.field", json_decode($value)));
            }
        }
        if (isset($criteria['denomination']) && !empty($criteria['denomination'])) {
            $search = $criteria['denomination'];
            unset($criteria['denomination']);
            foreach ($search as $key => $value) {
                $orX->add($qb->expr()->in("e.denomination", json_decode($value)));
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
    public function countSearchByCriteria(array $criteria = []): int
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
        if (isset($criteria['searchArt']) && !empty($criteria['searchArt'])) {
            $search = $criteria['searchArt'];
            unset($criteria['searchArt']);
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
        if ((isset($criteria['field']) && !empty($criteria['field'])) || (isset($criteria['denomination']) && !empty($criteria['denomination']))) {
            $qb = $this->searchByOrArray($qb, $criteria);
            unset($criteria['field']);
            unset($criteria['denomination']);
        }
        $qb = $this->addCriteria($qb, $criteria);
        return $qb;
    }

    public function getTitleAutocompleteData(string $searchQuery)
    {
        $query = $this->createQueryBuilder('artWork');
        $query->where('LOWER(artWork.title) like :param');
        $query->setParameter('param', strtolower("%" . $searchQuery . "%"));
        $query->select('artWork.title');
        return $query->getQuery()->getResult();
    }

    public function getLocationData(ArtWork $artWork, $dataType)
    {
        $query = $this->createQueryBuilder('artWork');
        $query->leftJoin('artWork.movements', 'movements')
            ->leftJoin('movements.correspondents', 'correspondents')
            ->leftJoin('correspondents.establishment', 'establishment')
            ->leftJoin('establishment.subDivisions', 'subDivisions')
            ->leftJoin('subDivisions.locations', 'locations')
            ->leftJoin('locations.room', 'room')
            ->leftJoin('room.building', 'building')
            ->leftJoin('building.commune', 'commune');
        $query->where('artWork = :artWork')->setParameter('artWork', $artWork);
        if ($dataType == 'commune') {
            $query->select('commune.name')
                ->andWhere('commune.name IS NOT null')
                ->groupBy('commune.id');
        } elseif ($dataType == 'building') {
            $query->select('building.name')
                ->andWhere('building.name IS NOT null')
                ->groupBy('building.id');
        }
        return $query->getQuery()->getArrayResult();

    }

    private function addQueryFilter(QueryBuilder $query, string $searchQuery)
    {
        $subDql='';
        if(strlen($searchQuery)>0){
            foreach (self::$tableColumns as $key=>$columnData){
                if($columnData['search']){
                    $subDql.='LOWER('.$columnData['entity'].'.'.$columnData['field'].') like :searchQuery or ';
                }
            }
            $subDql =substr_replace($subDql,'', strrpos($subDql, 'or'), 2);
        }
        if(strlen($subDql)>0){
            $query->andWhere("($subDql)");
            $query->setParameter('searchQuery',strtolower('%' . strtolower($searchQuery) . '%'));
        }
        return $query;
    }

    private function addGlobalQueryFilter(QueryBuilder $query, $globalSearchQuery)
    {
        $dqlString ='';
        $dqlString.= "LOWER(artWork.title) like :searchString" ;
        $searchInt= intval($globalSearchQuery);
        if($searchInt>0){
            $dqlString .=" or artWork.id = :search";
            $dqlString .=" or artWork.length = :search";
            $dqlString .=" or artWork.width = :search";
            $dqlString .=" or artWork.height = :search";
            $dqlString .=" or artWork.depth = :search";
            $dqlString .=" or artWork.length = :search";
            $dqlString .=" or artWork.diameter = :search";
            $dqlString .=" or artWork.numberOfUnit = :search";
            $dqlString .=" or artWork.totalLength = :search";
            $dqlString .=" or artWork.totalWidth = :search";
            $dqlString .=" or artWork.totalHeight = :search";
        }
        if(strlen($dqlString)>0){
            $query->andWhere($dqlString)->setParameter('searchString','%' . strtolower($globalSearchQuery) . '%');
            if($searchInt>0){
                $query->setParameter('search',$searchInt);
            }
        }

       return $query;
    }

    /**
     * Return list of artworks by theire ids
     * @param $artWorksIds
     * @param string $sortBy
     * @param string $sort
     * @return int|mixed|string
     */
    public function getArtworksByIds($artWorksIds, $sortBy = "id", $sort="asc"){
        $query = $this->createQueryBuilder('artWork');
        $query->where('artWork.id IN (:artWorkIds)')
            ->orderBy("artWork.$sortBy", $sort)
            ->setParameter('artWorkIds',$artWorksIds);

        return $query->getQuery()->getResult();
    }

    /**
     * @param $page
     * @param $limit
     * @return mixed
     */
    public function getInProgressArtWorks($page = 1, $limit = 40) {
        $query = $this->createQueryBuilder('artWork');
        $query->where('artWork.isCreated = false')
            ->orderBy('artWork.id', 'DESC');
        $totalQuantity = count($query->getQuery()->getResult());
        if($page!=""){
            $query->setFirstResult(($page-1) * $limit);
        }
        if($limit && $limit!= ""){
            $query->setMaxResults($limit);
        }
        return ['result' => $query->getQuery()->getResult(), 'totalQuantity' => $totalQuantity];
    }
}
