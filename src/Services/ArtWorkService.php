<?php


namespace App\Services;


use App\Entity\ArtWork;
use App\Entity\DepositStatus;
use App\Entity\Furniture;
use App\Entity\Photography;
use App\Entity\PhotographyType;
use App\Entity\PropertyStatus;
use App\Exception\FormValidationException;
use App\Form\ArtWorkType;
use App\Model\ApiResponse;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class ArtWorkService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var  ApiManager
     */
    private $apiManager;
    /**
     * @var FurnitureService
     */
    private $furnitureService;

    /**
     * ArtWorkService constructor.
     * @param EntityManagerInterface $entityManager
     * @param ApiManager $apiManager
     */
    public function __construct(EntityManagerInterface $entityManager, ApiManager $apiManager,FurnitureService $furnitureService)
    {
        $this->entityManager = $entityManager;
        $this->apiManager = $apiManager;
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
        if (count($records) === 1) {
            return ['result' => $records[0], 'previousId' => null, 'nextId' => null];
        }
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

            $options = $type == 'description' ? explode(",", $query['descriptiveWords']) : explode(" ", $query['title']);
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
     * @param Request $request
     * @param FormInterface $form
     * @param FurnitureService $furnitureService
     * @return array
     * @throws \Exception
     */
    public function createNotice(Request $request,FormInterface $form, FurnitureService $furnitureService, $status) {
        $data =$this->apiManager->getPostDataFromRequest($request, true);
        $form->submit($data);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($status == 'deposit') {
                if (!$form->getData()->getField() || !$form->getData()->getDenomination() || !$form->getData()->getTitle() || !$form->getData()->getStatus()->getDepositDate() || !$form->getData()->getStatus()->getStopNumber()) {
                    return ['msg' => 'Notice enregistrée en mode brouillon avec succès', 'res' => $this->apiManager->save($form->getData())];
                } else {
                    $attribues = $furnitureService->getAttributesByDenominationIdAndFieldId($form->getData()->getDenomination()->getId(), $form->getData()->getField()->getId());
                    if ((in_array('materialTechnique', $attribues) && $form->getData()->getMaterialTechnique()->isEmpty()) || (in_array('numberOfUnit', $attribues) && !$form->getData()->getNumberOfUnit())) {
                        return ['msg' => 'Notice enregistrée en mode brouillon avec succès', 'res' => $this->apiManager->save($form->getData())];
                    } else {
                        return ['msg' => 'Notice enregistrée avec succès en mode brouillon', 'res' => $this->apiManager->save($form->getData())];
                    }
                }
            } else {
                if (!$form->getData()->getField() || !$form->getData()->getDenomination() || !$form->getData()->getTitle() || !$form->getData()->getStatus()->getEntryMode() || !$form->getData()->getStatus()->getEntryDate() || !$form->getData()->getStatus()->getCategory()) {
                    return ['msg' => 'Notice enregistrée en mode brouillon avec succès', 'res' => $this->apiManager->save($form->getData())];
                } else {
                    $attribues = $furnitureService->getAttributesByDenominationIdAndFieldId($form->getData()->getDenomination()->getId(), $form->getData()->getField()->getId());
                    if ((in_array('materialTechnique', $attribues) && $form->getData()->getMaterialTechnique()->isEmpty()) || (in_array('numberOfUnit', $attribues) && !$form->getData()->getNumberOfUnit())) {
                        return ['msg' => 'Notice enregistrée en mode brouillon avec succès', 'res' => $this->apiManager->save($form->getData())];
                    } else {
                        return ['msg' => 'Notice enregistrée en mode brouillon avec succès', 'res' => $this->apiManager->save($form->getData())];
                    }
                }
            }

        } else {
            throw new FormValidationException($form);
        }

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

        /**
         * @var ArtWork $furniture
         */
        if((!$principalPhoto instanceof Photography && $photoType!==PhotographyType::TYPE['principle'])){
            $furniture->setIsCreated(false);
            $this->apiManager->save($furniture);
            return false;
        }
        if($photography && $principalPhoto) {
            if (($photography->getId() !== $principalPhoto->getId()) && $photoType === PhotographyType::TYPE['principle']) {
                return ['msg' => 'Photographie de type ' . PhotographyType::TYPE['principle'] . ' existe déjà', 'code' => 400];
            }
            if (($principalPhoto->getId() === $photography->getId()) && $photoType === PhotographyType::TYPE['principle']) {
                $isCreated = $this->checkFurniture($furniture);
                $furniture->setIsCreated($isCreated);
                $this->apiManager->save($furniture);
            }
        }

    }
    public function checkFurniture(Furniture $furniture){
        $status = ($furniture->getStatus() instanceof  DepositStatus)?ArtWorkType::DEPOSIT_STATUS:ArtWorkType::PROPERTY_STATUS;
        if (!$furniture->getField() || !$furniture->getDenomination() || !$furniture->getTitle()){
            return false;
            }

        if($status==(ArtWorkType::DEPOSIT_STATUS)){
            if (!$furniture->getStatus()->getDepositDate() || !$furniture->getStatus()->getStopNumber()) {
                return false;
            }
        }else {
            if (!$furniture->getStatus()->getEntryMode() || !$furniture->getStatus()->getEntryDate() || !$furniture->getStatus()->getCategory()) {
                return false;
            }
        }
        $attribues = $this->furnitureService->getAttributesByDenominationIdAndFieldId($furniture->getDenomination()->getId(), $furniture->getField()->getId());

        if((in_array('materialTechnique', $attribues) && $furniture->getMaterialTechnique()->isEmpty()) ||
            (in_array('numberOfUnit', $attribues) && !$furniture->getNumberOfUnit())) {
            return false;
        }
        $principalPhoto=$furniture->getPrincipalPhoto();
        if(!$principalPhoto instanceof Photography){
            dd(!$principalPhoto instanceof Photography);
            return false;
        }
            return true;



    }
}
