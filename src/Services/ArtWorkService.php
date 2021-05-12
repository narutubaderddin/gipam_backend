<?php


namespace App\Services;


use App\Entity\ArtWork;
use App\Entity\Furniture;
use App\Entity\PropertyStatus;
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
        $this->entityManager = $entityManager;
    }

    /**
     * @param ParamFetcherInterface $paramFetcher
     * @param $filter
     * @param $advancedFilter
     * @param $headerFilters
     * @param $query
     * @return ApiResponse
     */
    public function findArtWorkRecord(ParamFetcherInterface $paramFetcher, $filter, $advancedFilter, $headerFilters): ApiResponse
    {
        $page = $paramFetcher->get('page', true) ?? 1;
        $limit = $paramFetcher->get('limit', true) ?? 5;
        $sortBy = $paramFetcher->get('sort_by') ?? 'id';
        $sort = $paramFetcher->get('sort') ?? 'asc';
        $query= $paramFetcher->get('search')??'';
        $globalQuery= $paramFetcher->get('globalSearch')??'';
        $result = $this->entityManager->getRepository(ArtWork::class)->getArtWorkList($filter, $advancedFilter, $headerFilters,$query,$globalQuery, $page, $limit, $sortBy, $sort);
        $filtredQuantity = $this->entityManager->getRepository(ArtWork::class)->getArtWorkList($filter, $advancedFilter, $headerFilters,$query,$globalQuery, $page, $limit, $sortBy, $sort, true);

        return new ApiResponse(
            $page,
            $limit,
            $filtredQuantity,
            $this->entityManager->getRepository(ArtWork::class)->count([]),
            $result
        );

    }

    public function findAutocompleteData($searchQuery, $type)
    {

        if ($type != 'description') {
            $queryData = $this->entityManager->getRepository(ArtWork::class)->getTitleAutocompleteData($searchQuery);
        } else {
            $queryData = $this->entityManager->getRepository(PropertyStatus::class)->getDescriptionAutocompleteData($searchQuery);
        }

        $result = [];
        foreach ($queryData as $query) {

            $options = $type == 'description' ? explode(" ", $query['descriptiveWords']) : explode(" ", $query['title']);
            foreach ($options as $option) {
                $option = strtolower(preg_replace('/[^A-Za-z0-9\-]/', '', $option)); // Removes special chars.
                if (strpos($option, strtolower($searchQuery)) !== false && !in_array($option, $result)) {
                    $result[] = $option;
                }
            }


        }
        return $result;
    }

    public function getArtWorkLocationData(ArtWork $artWork, $dataType)
    {
        return $this->entityManager->getRepository('Main:ArtWork')
            ->getLocationData($artWork, $dataType);
    }

}