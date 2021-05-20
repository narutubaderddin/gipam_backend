<?php

namespace App\Services;

use App\Model\ApiResponse;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ApiManager
 * @package App\Services
 */
class ApiManager
{
    const DEFAULT_PARAMS = ['page', 'limit', 'sort_by', 'sort'];
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
     * @return ApiResponse
     */
    public function findRecordsByEntityName(string $fqcn, ParamFetcherInterface $paramFetcher): ApiResponse
    {
        $page = $paramFetcher->get('page', true)?? 1;
        $limit = $paramFetcher->get('limit', true)?? 0;
        $sortBy = $paramFetcher->get('sort_by')?? 'id';
        $sort = $paramFetcher->get('sort')?? 'asc';
        $search = $paramFetcher->get('search')?? null;
        $criteria = $this->getCriteriaFromParamFetcher($paramFetcher);
        $offset = $this->getOffsetFromPageNumber($page, $limit);
        $repo = $this->em->getRepository($fqcn);
        return new ApiResponse(
            $page,
            $limit,
            $repo->countByCriteria($criteria, $search),
            $repo->count([]),
            $repo->findByCriteria($criteria, $offset, $limit, $sortBy, $sort, $search)
        );
    }

    /**
     * @param int $page
     * @param int $limit
     * @return int
     */
    private function getOffsetFromPageNumber(int $page, int $limit) : int
    {
        if(1 < $page){
            return $limit * ($page-1);
        }
        return 0;
    }

    /**
     * @param ParamFetcherInterface $paramFetcher
     * @return array
     */
    private function getCriteriaFromParamFetcher(ParamFetcherInterface $paramFetcher): array
    {
        $criteria = [];
        $annotations = $paramFetcher->getParams();
        $values = $paramFetcher->all();

        foreach ($values as $name => $value) {
            if ($name == 'search')
            {
                continue;
            }
            if (null !== $value
                && isset($annotations[$name])
                && $annotations[$name] instanceof QueryParam
                && !\in_array($name, self::DEFAULT_PARAMS)
            ) {
                $criteria[$name] = $paramFetcher->get($name);
            }
        }

        return $criteria;
    }

    /**
     * Recursive function to display members of array with indentation
     *
     * @param array $arr Array to process
     * @return array
     * @throws \Exception
     */
    function formatArrayValue($arr) {
        $result = [];
        if ($arr) {
            foreach ($arr as $key => $value) {
                if (is_array($value)) {
                    $result[$key] = $this->formatArrayValue($value);
                } else {
                    if ($value == "null") {
                        $result[$key] = null;
                    } else {
                        if (in_array($key, ['date','entryDate', 'insuranceValueDate'])) {
                            $result[$key] = date($value);
                        }  elseif (in_array($key, ['creationDate'])) {
                            $result[$key] = date(json_decode($value).'-01-01');
                        } elseif (in_array($key, ['materialTechnique', 'authors'])) {
                            $array = ['['.$value.']'];
                            $result[$key] = json_decode($array[0]);
                        }  elseif (in_array($key, ['url','descriptiveWords', 'title', 'marking', 'registrationSignature','insuranceValue', 'otherRegistrations', 'description_commentaire'])) {
                            $result[$key] = $value;
                        }else{
                            $result[$key] = (int)json_decode($value);
                        }
                    }

                }
            }
        }
        return $result;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function getPostDataFromRequest(Request $request, ?bool  $noticeCreation = false):array
    {
        $data = $request->request->all();
        if ($noticeCreation) {
            $data = $this->formatArrayValue($data);
        }
        foreach($request->files->all() as $key => $file){
            if (isset($data[$key])){
                foreach ($data[$key] as $k => &$value){
                    $value = array_merge($value, $file[$k]);
                }
            }else{
                $data[$key] = $file;
            }
        }
        return $data;
    }

    /**
     * @param string $fgcn
     * @param ParamFetcherInterface $paramFetcher
     * @return ApiResponse
     */
    public function findRecordsByEntityNameAndCriteria(string $fgcn,ParamFetcherInterface $paramFetcher):ApiResponse
    {
        $page =(int) $paramFetcher->get('page', true)?? 1;
        $limit =(int) $paramFetcher->get('limit', true)?? 0;
        $repo = $this->em->getRepository($fgcn);
        $filteredCount = $repo->findRecordsByEntityNameAndCriteria($paramFetcher,true);
        $record = $repo->findRecordsByEntityNameAndCriteria($paramFetcher,false,$page,$limit);

        return  new ApiResponse(
            $page,
            $limit,
            $filteredCount,
            $repo->count([]),
            $record
        );

    }


    /**
     * @param string $fqcn
     * @param ParamFetcherInterface $paramFetcher
     * @return ApiResponse
     */
    public function searchByEntityName(string $fqcn, ParamFetcherInterface $paramFetcher): ApiResponse
    {
        $page = $paramFetcher->get('page', true)?? 1;
        $limit = $paramFetcher->get('limit', true)?? 20;
        $sortBy = $paramFetcher->get('sort_by')?? 'id';
        $sort = $paramFetcher->get('sort')?? 'asc';
        $criteria = $this->getCriteriaFromParamFetcher($paramFetcher);
        $offset = $this->getOffsetFromPageNumber($page, $limit);
        $repo = $this->em->getRepository($fqcn);

        return new ApiResponse(
            $page,
            $limit,
            $repo->countSearchByCriteria($criteria),
            $repo->count([]),
            $repo->searchByCriteria($criteria, $offset, $limit, $sortBy, $sort)
        );


    }
}
