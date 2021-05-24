<?php

namespace App\Controller\API;

use App\Entity\ArtWork;
use App\Entity\DepositStatus;
use App\Exception\FormValidationException;
use App\Form\ArtWorkType;
use App\Repository\ArtWorkRepository;
use App\Services\ApiManager;
use App\Services\FurnitureService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Swagger\Annotations as SWG;

/**
 * Class NoticeController
 * @package App\Controller\API
 * @Route("/notices")
 */
class NoticeController extends AbstractFOSRestController
{

    /**
     * @var ApiManager
     */
    protected $apiManager;

    public function __construct(
        ApiManager $apiManager
    )
    {
        $this->apiManager = $apiManager;
    }

    /**
     * @Rest\Get("/attributes", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns attributes"
     * )
     * @SWG\Tag(name="notices")
     *
     * @Rest\QueryParam(name="denomination_id", requirements="\d+", nullable=true, description="id denomination")
     * @Rest\QueryParam(name="field_id", requirements="\d+", nullable=true, description="id field")
     * @Rest\View()
     *
     * @param ParamFetcherInterface $paramFetcher
     * @param FurnitureService $furnitureService
     *
     * @return View
     */
    public function getAttributes(ParamFetcherInterface $paramFetcher, FurnitureService $furnitureService)
    {
        $fieldId = $paramFetcher->get('field_id', true);
        $denominationId = $paramFetcher->get('denomination_id', true);
        $attributes = $furnitureService->getAttributesByDenominationIdAndFieldId($denominationId, $fieldId);

        return $this->view($attributes, Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/deposit")
     *
     * @SWG\Tag(name="notices")
     * @SWG\Response(
     *     response=201,
     *     description="Returns created ArtWork",
     *     @SWG\Schema(
     *         ref=@Model(type=ArtWork::class, groups={"artwork"})
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Creation error"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Add ArtWork",
     *     @Model(type=ArtWork::class, groups={"artwork"})
     * )
     *
     * @Rest\View(serializerGroups={"artwork"}, serializerEnableMaxDepthChecks=true)
     * @param Request $request
     *
     * @param FurnitureService $furnitureService
     * @return View
     * @throws \Exception
     */
    public function createDepositNotice(Request $request, FurnitureService $furnitureService)
    {
        $artWork = new ArtWork();
        $form = $this->createArtWorkForm(ArtWorkType::DEPOSIT_STATUS, $artWork);
        return $this->createNotice($request, $form, $furnitureService);
    }

    /**
     * @param ArtWork $artWork
     * @param Request $request
     ** @Rest\Patch("/{id}",requirements={"id"="\d+"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns updated Art Work",
     *     @SWG\Schema(
     *         ref=@Model(type=ArtWork::class, groups={"artwork", "id"})
     *     )
     * )
     * @SWG\Response(
     *     response="400",
     *     description="update error"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     @Model(type=ArtWork::class, groups={""}),
     *     description="update Art Work")
     * @SWG\Tag(name="notices")
     * @Rest\View(serializerGroups={"artwork"},serializerEnableMaxDepthChecks=true)
     * @return View
     * @throws \Exception
     */
    public function updateArtWork(ArtWork $artWork,Request $request){
        $status = ($artWork->getStatus() instanceof  DepositStatus)?ArtWorkType::DEPOSIT_STATUS:ArtWorkType::PROPERTY_STATUS;
        $form = $this->createArtWorkForm($status,$artWork);
        $form->submit($this->apiManager->getPostDataFromRequest($request),false);
        if($form->isValid()){
            $artWork = $this->apiManager->save($form->getData());
            return $this->view($artWork,Response::HTTP_OK);
        }
        throw new FormValidationException($form);
    }

    /**
     * @param $status
     * @param null $data
     * @return FormInterface
     */
    private function createArtWorkForm($status,$data=null)
    {

        return $this->createForm(ArtWorkType::class,$data,[
            'status'=>$status
        ]);

    }

    /**
     * @Rest\Post("/property")
     *
     * @SWG\Tag(name="notices")
     * @SWG\Response(
     *     response=201,
     *     description="Returns created ArtWork",
     *     @SWG\Schema(
     *         ref=@Model(type=ArtWork::class, groups={"artwork"})
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Creation error"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Add ArtWork",
     *     @Model(type=ArtWork::class, groups={"artwork"})
     * )
     *
     * @Rest\QueryParam(name="id", requirements="\d+", default="null", description="id of notice if exists")
     * @Rest\View(serializerGroups={"artwork", "art_work_details", "id"}, serializerEnableMaxDepthChecks=true)
     *
     * @param Request $request
     *
     * @param FurnitureService $furnitureService
     * @param ParamFetcherInterface $paramFetcher
     * @param ArtWorkRepository $artWorkRepository
     * @return View
     * @throws \Exception
     */
    public function createPropertyNotice(Request $request, FurnitureService $furnitureService, ParamFetcherInterface $paramFetcher, ArtWorkRepository $artWorkRepository)
    {
        $artWork = new ArtWork();
        $form =  $this->createArtWorkForm( ArtWorkType::PROPERTY_STATUS, $artWork);
        return $this->createNotice($request, $form, $furnitureService);
    }

    /**
     * @Rest\Patch("/update-in-progress-notice/{id}")
     *
     * @SWG\Tag(name="notices")
     * @SWG\Response(
     *     response=200,
     *     description="Returns updated Art Work",
     *     @SWG\Schema(
     *         ref=@Model(type=ArtWork::class, groups={"artwork", "id"})
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Creation error"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="update progress ArtWork",
     *     @Model(type=ArtWork::class, groups={"artwork"})
     * )
     * @Rest\View(serializerGroups={"artwork", "art_work_details", "id"}, serializerEnableMaxDepthChecks=true)
     *
     * @param Request $request
     *
     * @param FurnitureService $furnitureService
     * @param ArtWork $artWork
     * @return View
     * @throws \Exception
     */
    public function updateInProgressNotice(Request $request, FurnitureService $furnitureService, ArtWork $artWork)
    {
        $status = ($artWork->getStatus() instanceof  DepositStatus)?ArtWorkType::DEPOSIT_STATUS:ArtWorkType::PROPERTY_STATUS;
        $form = $this->createArtWorkForm($status,$artWork);
        $data = $this->apiManager->getPostDataFromRequest($request, true);
        $form->submit($data, false);
        if($form->isValid()){
            $artWork = $this->apiManager->save($form->getData());
            $formattedResult = ['msg' => 'Notice enregistrée en mode brouillon avec succès', 'res' => $artWork];

            return $this->view($formattedResult,Response::HTTP_OK);
        }
        throw new FormValidationException($form);
    }

    /**
     * @param Request $request
     * @param FormInterface $form
     * @param FurnitureService $furnitureService
     * @return View
     * @throws \Exception
     */
    public function createNotice(Request $request,FormInterface $form, FurnitureService $furnitureService) {
        $data =$this->apiManager->getPostDataFromRequest($request, true);
        $form->submit($data);
        if ($form->isSubmitted() && $form->isValid()) {
            if (!$form->getData()->getField() || !$form->getData()->getDenomination() || !$form->getData()->getTitle() || !$form->getData()->getStatus()->getEntryMode() || !$form->getData()->getStatus()->getEntryDate() || !$form->getData()->getStatus()->getCategory()) {
                $formattedResult = ['msg' => 'Notice enregistrée en mode brouillon avec succès', 'res' => $this->apiManager->save($form->getData())];
                return $this->view($formattedResult, Response::HTTP_CREATED);
            } else {
                $attribues = $furnitureService->getAttributesByDenominationIdAndFieldId($form->getData()->getDenomination()->getId(), $form->getData()->getField()->getId());
                if ((in_array('materialTechnique', $attribues) && $form->getData()->getMaterialTechnique()->isEmpty()) || (in_array('numberOfUnit', $attribues) && !$form->getData()->getNumberOfUnit())) {
                    $formattedResult = ['msg' => 'Notice enregistrée en mode brouillon avec succès', 'res' => $this->apiManager->save($form->getData())];
                    return $this->view($formattedResult, Response::HTTP_CREATED);
                } else {
                    $form->getData()->setIsCreated(true);
                    $formattedResult = ['msg' => 'Notice enregistrée avec succès', 'res' => $this->apiManager->save($form->getData())];
                    return $this->view($formattedResult, Response::HTTP_CREATED);
                }
            }
        } else {
            throw new FormValidationException($form);
        }
    }

    /**
     * @Rest\Get("/get-art-works-in-progress")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of an ArtWork in progress",
     *     @SWG\Schema(
     *         @SWG\Items(ref=@Model(type=ApiResponse::class))
     *     )
     * )
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="page number.")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="20", description="page size.")
     * @SWG\Tag(name="art_works_in_progress")
     * @Rest\View(serializerGroups={"short","photography", "hyperLink_furniture", "attachment"},serializerEnableMaxDepthChecks=true)
     * @param ArtWorkRepository $artWorkRepository
     * @return View
     */
    public function getInProgressArtWorks(ArtWorkRepository $artWorkRepository, ParamFetcherInterface $paramFetcher) {
        $page = $paramFetcher->get('page');
        $limit = $paramFetcher->get('limit');
        $records = $artWorkRepository->getInProgressArtWorks($page, $limit);

        return $this->view($records, Response::HTTP_OK);
    }
}
