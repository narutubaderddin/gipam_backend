<?php


namespace App\Services;


use App\Entity\ArtWork;
use App\Entity\Furniture;
use App\Entity\Photography;
use App\Entity\PropertyStatus;
use App\Exception\FormValidationException;
use App\Model\ApiResponse;
use App\Repository\FurnitureRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ArtWorkService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var FurnitureService
     */
    private $furnitureService;

    public function __construct(EntityManagerInterface $entityManager,FurnitureService $furnitureService)
    {
        $this->entityManager = $entityManager;
        $this->furnitureService=$furnitureService;
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
        $totalQuantity = $this->entityManager->getRepository(ArtWork::class)->getArtWorkList($filter, $advancedFilter, $headerFilters,$query,$globalQuery, $page, $limit, $sortBy, $sort, false,true);
        return new ApiResponse(
            $page,
            $limit,
            $filtredQuantity,
            $totalQuantity,
            $result
        );

    }

    /**
     * @param ParamFetcherInterface $paramFetcher
     * @param $filter
     * @param $advancedFilter
     * @param $headerFilters
     * @return array
     */
    public function findArtWorkDetails(ParamFetcherInterface $paramFetcher, $filter, $advancedFilter, $headerFilters)
    {
        $offset = $paramFetcher->get('offset', true) ?? 0;
        $sortBy = $paramFetcher->get('sort_by') ?? 'id';
        $sort = $paramFetcher->get('sort') ?? 'asc';
        $query = $paramFetcher->get('search') ?? '';
        $globalQuery = $paramFetcher->get('globalSearch') ?? '';
        $repoOffset = $offset;
        if ($offset == 0) {
            $limit = 2;
        } else {
            $repoOffset = $offset - 1;
            $limit = 3;
        }
        $records = $this->entityManager->getRepository(ArtWork::class)
            ->getArtWorkListByOffset($filter, $advancedFilter, $headerFilters, $query, $globalQuery, $repoOffset, $limit, $sortBy, $sort);
        $record = null;
        if ($offset == 0) {
            $previousId = null;
            $nextId = ($records[1])->getId();
            $record = $records[0];
        } else {
            $previousId = isset($records[0]) ? ($records[0])->getId() : null;
            $nextId = isset($records[2]) ? ($records[2])->getId() : null;
            $record = $records[1] ?? null;
        }
        return ['result' => $record, 'previousId' => $previousId, 'nextId' => $nextId];
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

    /**
     * Return list of artWorks by ids and sorted.
     * @param $artWorks
     * @param string $sortBy
     * @param string $sort
     * @return mixed
     */
    public function getArtWorksByIds($artWorks, $sortBy = "id", $sort="asc"){

        return $this->entityManager->getRepository(ArtWork::class)
            ->getArtworksByIds($artWorks, $sortBy, $sort);
    }

    /**
     * @param Furniture $furniture
     * @param Photography $photography
     * @param $photoType
     * @return array|false
     */
    public function checkPrincipalPhoto(Furniture $furniture, Photography $photography, $photoType)
    {
        $principalPhoto=$furniture->getPrincipalPhoto();

        $attribues = $this->furnitureService->getAttributesByDenominationIdAndFieldId($furniture->getDenomination()->getId(), $furniture->getField()->getId());
        /**
         * @var ArtWork $furniture
         */
        if((!$principalPhoto instanceof Photography && $photoType!=='Identification')){
            $furniture->setIsCreated(false);
            return false;
        }
        if (($photography->getId() !== $principalPhoto->getId() ) && $photoType==='Identification') {
                return ['msg' => $principalPhoto->getId(). 'Photographie de type "Identification" existe déjà', 'code' => 400];
        }
        if(($principalPhoto->getId()===$photography->getId()) && $photoType==='Identification'){
            if((in_array('materialTechnique', $attribues) && $furniture->getMaterialTechnique()->isEmpty()) ||
                (in_array('numberOfUnit', $attribues) && !$furniture->getNumberOfUnit())){
                $furniture->setIsCreated(false);
            }else {
                $furniture->setIsCreated(true);
            }
        }

    }
}
