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
     * @param Request $request
     * @return array
     */
    public function getPostDataFromRequest(Request $request):array
    {
        $data = $request->request->all();
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
}