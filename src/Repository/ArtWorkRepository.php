<?php

namespace App\Repository;

use App\Entity\ArtWork;
use App\Entity\DepositStatus;
use App\Entity\PropertyStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Self_;

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
    private static $entityInstance=[
        'depot'=>DepositStatus::class,
        'propriete'=>PropertyStatus::class
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
    ];

    /**
     * @param array $filter
     * @param array $advancedFilter
     * @param array $headerFilters
     * @param $page
     * @param $limit
     * @param bool $count
     * @return int|mixed|string
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function getArtWorkList(array $filter, array $advancedFilter, array $headerFilters, $page, $limit,$sortBy='id',$sort='desc',$count=false)
    {
        $query = $this->createQueryBuilder('artWork');
        $query->where($query->expr()->isInstanceOf('artWork', ArtWork::class));
        $query->leftJoin('artWork.field', 'field')
            ->leftJoin('artWork.denomination', 'denomination')
            ->leftJoin('artWork.materialTechnique', 'materialTechnique')
            ->leftJoin('artWork.authors', 'authors')
            ->leftJoin('artWork.era', 'era')
            ->leftJoin('artWork.style', 'style')
            ->leftJoin('artWork.movements','movements')
            ->leftJoin('movements.correspondents','correspondents')
            ->leftJoin('correspondents.establishment','establishment')
            ->leftJoin('establishment.ministry','ministry')
            ->leftJoin('establishment.type','establishement_type')
            ->leftJoin('establishment.subDivisions','sub_divisions')
            ->leftJoin('sub_divisions.locations','locations')
            ->leftJoin('locations.type','location_type')
            ->leftJoin('locations.room','room')
            ->leftJoin('room.building','building')
            ->leftJoin('building.site','site')
            ->leftJoin('building.commune','commune')
            ->leftJoin('building.responsibles','responsibles')
            ->leftJoin('commune.department','department')
            ->leftJoin('department.region','region')
            ->leftJoin('movements.type','movementType')
            ->leftJoin('movementType.movementActionTypes','movementActionTypes')
            ->leftJoin('artWork.reports','reports')
            ->leftJoin('reports.reportSubType','reportSubType')
            ->leftJoin('reportSubType.reportType','reportType')
            ->leftJoin('reports.actions','reportAction')
            ->leftJoin('reportAction.type','reportActionType')
            ->leftJoin('artWork.status','status')
            ->leftJoin(DepositStatus::class,'depositStatus',\Doctrine\ORM\Query\Expr\Join::WITH,'depositStatus = status')
            ->leftJoin(PropertyStatus::class,'propertyStatus',\Doctrine\ORM\Query\Expr\Join::WITH,'propertyStatus = status')
            ->leftJoin('depositStatus.depositor','depositor')
            ->leftJoin('propertyStatus.category','category')
            ->leftJoin('propertyStatus.entryMode','entryMode')
//                        ->leftJoin('sub_divisions.services','services')
        ;
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
        if($count){
            $query->select('count(artWork.id)');
            return  $query->getQuery()->getSingleScalarResult();
        }
        $query->setFirstResult(($page-1)*$limit)->setMaxResults($limit);
        if(array_key_exists($sortBy,self::$columns)){
            $sortDataKey=self::$columns[$sortBy]['entity'].'.'.self::$columns[$sortBy]['field'];
            $query->orderBy($sortDataKey,$sort);
        }
        return $query->getQuery()->getResult();
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
                        $subDql .= 'LOWER('.$entity . '.' . $queryKey . ') like :' . $column;
                        if ($isColumnHeaderFilterExist) {
                            $subDql .= " and ";
                        }
                    }
                    if ($isColumnHeaderFilterExist) {
                        $subDql .='LOWER('.$entity . '.' . $queryKey . ') like :header' . $column;
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
                        $data = ($isColumnFilterExist&& is_array($filter[$column]) && count($filter[$column]) > 0) ? array_unique(array_merge($data, $filter[$column])) : $data;
                        $data = ($isColumnHeaderFilterExist && is_array($headerFilters[$column]) &&count($headerFilters[$column]) > 0) ? array_unique(array_merge($data, $headerFilters[$column])) : $data;
                        $query->andWhere($entity . '.' . $queryKey . ' in (:' . $column . ')');
                        $query->setParameter($column, $data, Connection::PARAM_STR_ARRAY);
                    }
                    break;
                case 'instance':
                    $data=[];
                    $data = ($isColumnFilterExist&& is_array($filter[$column]) && count($filter[$column]) > 0) ? array_unique(array_merge($data, $filter[$column])) : $data;
                    $data = ($isColumnHeaderFilterExist && is_array($headerFilters[$column]) &&count($headerFilters[$column]) > 0) ? array_unique(array_merge($data, $headerFilters[$column])) : $data;
                    $subDql="";$i=0;
                    foreach ($data as $entityData){
                        if(array_key_exists($entityData,self::$entityInstance)){
                            $subDql .= "($entity INSTANCE of :entity_$i)";
                            if($entityData !== end($data)) {
                                $subDql.=' or ';
                            }
                            $i++;
                        }

                    }
                    if(count($data)>0 && strlen($subDql)>0){
                        $query->andWhere("($subDql)");$i=0;
                        foreach ($data as $entityData){
                            if(array_key_exists($entityData,self::$entityInstance)){
                                $query->setParameter("entity_$i",$this->getEntityManager()->getClassMetadata(self::$entityInstance[$entityData]));
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
        $keysOperator ="";
        $operator ='and';
        foreach ($advancedFilter as $key => $value) {
            $subDqlString = "";
            $isColumnAdvancedFilterExist =array_key_exists($key, self::$columns) && isset($advancedFilter[$key]) && $advancedFilter[$key]['value'] != "";
            $isColumnHeaderFilterExist =array_key_exists($key, self::$columns) && isset($headerFilter[$key]) && $headerFilter[$key] != "";

            if ($isColumnHeaderFilterExist || $isColumnAdvancedFilterExist) {
                $dqlOperator="";
                $keyData = self::$columns[$key];
                switch ($keyData['operator']) {
                    case'in':
                        if(($isColumnAdvancedFilterExist &&count($value['value'])>0) || ($isColumnHeaderFilterExist&&count($headerFilter[$key])>0)){
                            $dqlOperator = (is_array($value)&&$operator== 'not')  ? ' not in' : ' in';
                            $subDqlString = $keyData['entity'] . '.' . $keyData['field'] . $dqlOperator . ' (:' . $key . ')';
                        }
                        break;
                    case 'equal':
                        $dqlOperator = (is_array($value)&&$operator== 'not') ? ' !=' : ' =';
                        $subDqlString = $keyData['entity'] . '.' . $keyData['field'] . $dqlOperator . ' :' . $key . '';
                        break;
                    case 'range':
                    case 'equalDate':
                        if ($operator != 'not') {
                            $subDqlString .= $keyData['entity'] . '.' . $keyData['field'] . ' between :value1 and :value2';
                        }else{
                            $subDqlString.= $keyData['entity']. '.' . $keyData['field'].' > :value1 and '.$keyData['entity']. '.' . $keyData['field'].' < :value2';
                        }
                        break;

                }
                $dqlString .= (strlen($subDqlString)>0)?  $keysOperator. "( " . $subDqlString . " )":"";
                if ($i < count($advancedFilter) &&  strlen($subDqlString)>0) {
                    $keysOperator = (is_array($value)&&$operator== 'or')  ? 'or' : 'and';
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
            $isColumnAdvancedFilterExist =array_key_exists($key, self::$columns) && isset($advancedFilter[$key]) && $advancedFilter[$key] ['value']!= "";
            $isColumnHeaderFilterExist =array_key_exists($key, self::$columns) && isset($headerFilter[$key]) && $headerFilter[$key] != "";
            if ($isColumnHeaderFilterExist || $isColumnAdvancedFilterExist) {
                $keyData = self::$columns[$key];
                switch ($keyData['operator']) {
                    case 'in':
                        $data = [];
                        $data = ($isColumnAdvancedFilterExist&& is_array($value['value']) && count($value['value']) > 0) ? array_unique(array_merge($data, $value['value'])) : $data;
                        $data = ($isColumnHeaderFilterExist && is_array($headerFilter[$key]) &&count($headerFilter[$key]) > 0) ? array_unique(array_merge($data, $headerFilter[$key])) : $data;
                        if(count($data)>0){
                            $query->setParameter($key,$data);
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
                        $startDate->setDate($value['value'],1,1);
                        $startDate->setTime(0,0,0);
                        $endDate = new \DateTime();
                        $endDate->setDate($value['value'],12,31);
                        $endDate->setTime(23,59,59);
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
    public function countSearchByCriteria(array $criteria = []) : int
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
        if((isset($criteria['field']) && !empty($criteria['field']))||(isset($criteria['denomination']) && !empty($criteria['denomination']))){
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
        $query->setParameter('param',strtolower("%".$searchQuery."%"));
        $query->select('artWork.title');
        return $query->getQuery()->getResult();
    }
    public function getLocationData(ArtWork $artWork,$dataType){
        $query = $this->createQueryBuilder('artWork');
        $query->leftJoin('artWork.movements','movements')
            ->leftJoin('movements.correspondents','correspondents')
            ->leftJoin('correspondents.establishment','establishment')
            ->leftJoin('establishment.subDivisions','subDivisions')
            ->leftJoin('subDivisions.locations','locations')
            ->leftJoin('locations.room','room')
            ->leftJoin('room.building','building')
            ->leftJoin('building.commune','commune');
        $query->where('artWork = :artWork')->setParameter('artWork',$artWork);
        if($dataType =='commune'){
            $query->select('commune.name')
                ->andWhere('commune.name IS NOT null')
                ->groupBy('commune.id')
            ;
        }elseif ($dataType == 'building'){
            $query->select('building.name')
                ->andWhere('building.name IS NOT null')
                ->groupBy('building.id')
            ;
        }
        return $query->getQuery()->getArrayResult();

    }
}
