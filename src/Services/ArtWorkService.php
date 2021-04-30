<?php


namespace App\Services;



use App\Entity\ArtWork;
use App\Entity\Furniture;
use App\Model\ApiResponse;
use App\Repository\FurnitureRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Request\ParamFetcherInterface;

class ArtWorkService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager =$entityManager;
    }

    /**
     * @param ParamFetcherInterface $paramFetcher
     * @param $filter
     * @param $advancedFilter
     * @param $headerFilters
     * @return ApiResponse
     */
    public function findArtWorkRecord(ParamFetcherInterface $paramFetcher ,$filter,$advancedFilter,$headerFilters):ApiResponse{
        $page = $paramFetcher->get('page', true)?? 1;
        $limit = $paramFetcher->get('limit', true)?? 5;
        $sortBy = $paramFetcher->get('sort_by')?? 'id';
        $sort = $paramFetcher->get('sort')?? 'asc';
        $result =$this->entityManager->getRepository(Furniture::class)->getArtWorkList($filter,$advancedFilter,$headerFilters,$page,$limit);
        $filtredQuantity =0;
        return  new ApiResponse(
            $page,
            $limit,
            $filtredQuantity,
            $this->entityManager->getRepository(ArtWork::class)->count([]),
            $result
        );

    }

}