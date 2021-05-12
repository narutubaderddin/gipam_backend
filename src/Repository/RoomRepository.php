<?php

namespace App\Repository;

use App\Entity\Room;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use FOS\RestBundle\Request\ParamFetcherInterface;

/**
 * @method Room|null find($id, $lockMode = null, $lockVersion = null)
 * @method Room|null findOneBy(array $criteria, array $orderBy = null)
 * @method Room[]    findAll()
 * @method Room[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoomRepository extends ServiceEntityRepository
{
    use RepositoryTrait;

    public const SEARCH_FIELDS = [
        'level_param' => 'level',
        'reference_param' => 'reference',
        'building_name_param' => 'building_name'
    ];

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Room::class);
    }
    public function findRecordsByEntityNameAndCriteria(ParamFetcherInterface $paramFetcher, $count, $page = 1, $limit = 0)
    {
        $departement = $paramFetcher->get('departement') ?? "";
        $region = $paramFetcher->get('region') ?? "";
        $commune = $paramFetcher->get('commune') ?? "";
        $batiment = $paramFetcher->get('batiment')??"";
        $site = $paramFetcher->get('site')??"";
        $query = $this->createQueryBuilder('room')
            ->leftJoin('room.building','building')
            ->leftJoin('site.buildings','building')
            ->leftJoin('building.commune','commune')
            ->leftJoin('commune.department','departement')
            ->leftJoin('departement.region','region')
        ;
        $query = $this->addArrayCriteriaCondition($query, $departement, 'departement');
        $query = $this->addArrayCriteriaCondition($query, $region, 'region');
        $query = $this->addArrayCriteriaCondition($query, $commune, 'commune');
        $query = $this->addArrayCriteriaCondition($query, $batiment, 'building');
        $query = $this->addArrayCriteriaCondition($query, $site, 'site');

        if ($count) {
            $query->select('count(commune.id)');
            return $query->getQuery()->getSingleScalarResult();
        }

        if ($page != "") {
            $query->setFirstResult(($page * $limit) + 1);
        }
        if ($limit && $limit != "") {
            $query->setMaxResults($limit);
        }

        return $query->getQuery()->getResult();
    }

    public function findRoomsLevelByCriteria( $batiment=[])
    {
        $query = $this->createQueryBuilder('room')
            ->leftJoin('room.building','building');

        $data = is_string($batiment) ? json_decode($batiment, true) : [];
        if ($data && !is_array($data)) {
            throw new \RuntimeException("batiments  value should be an array");
        }

        if (is_array($data) ) {
            $query->andWhere('building.id in (:data)')->setParameter('data',$data);
        }
        $query->select('distinct(room.level) as level');
        $result=[];
        foreach ($query->getQuery()->getResult() as $levelData){
            $result[]= $levelData['level'];
        }

      return $result;

    }

    public function findRoomsRefByCriteria( $building, $level)
    {
       $level = explode(',',$level);
       $building = explode(',',$building);
        $query = $this->createQueryBuilder('room')
            ->leftJoin('room.building','building');

        $query->andWhere('building.id in (:building)')->setParameter('building',$building);
        $query->andWhere('room.level in (:level)')->setParameter('level',$level);
        $query->select('distinct(room.reference) as reference');
        $result=[];
        foreach ($query->getQuery()->getResult() as $referenceData){
            $result[]= $referenceData['reference'];
        }

        return $result;

    }
}
