<?php

namespace App\Controller\API;

use App\Entity\ArtWork;
use App\Entity\DepositStatus;
use App\Exception\FormValidationException;
use App\Form\ArtWorkType;
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
     *
     * @Rest\View(serializerGroups={"artwork"}, serializerEnableMaxDepthChecks=true)
     *
     * @param Request $request
     *
     * @return View
     *
     */
    public function createDepositNotice(Request $request)
    {
        $form = $this->createArtWorkForm(ArtWorkType::DEPOSIT_STATUS);
        $form->submit($this->apiManager->getPostDataFromRequest($request));

        if ($form->isSubmitted() && $form->isValid()) {
            $artWork = $this->apiManager->save($form->getData());
            return $this->view($artWork, Response::HTTP_CREATED);
        } else {
            throw new FormValidationException($form);
        }
    }

    /**
     * @param ArtWork $artWork
     * @param Request $request
     * @Rest\Patch("/{id}",requirements={"id"="\d+"})
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
     */
    public function updateArtWork(ArtWork $artWork,Request $request){
        $status = ($artWork->getStatus() instanceof  DepositStatus)?ArtWorkType::DEPOSIT_STATUS:ArtWorkType::PROPERTY_STATUS;
        $form = $this->createArtWorkForm($status,$artWork);
        $form->submit($this->apiManager->getPostDataFromRequest($request));
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
     *
     * @Rest\View(serializerGroups={"artwork"}, serializerEnableMaxDepthChecks=true)
     *
     * @param Request $request
     * 
     * @return View
     *
     */
    public function createPropertyNotice(Request $request, FurnitureService $furnitureService)
    {
        $form =  $form = $this->createArtWorkForm(ArtWorkType::PROPERTY_STATUS);
        $form->submit($this->apiManager->getPostDataFromRequest($request));

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$form->getData()->getField() || !$form->getData()->getDenomination() || !$form->getData()->getTitle()) {
                $formattedResult = ['msg' => 'Notice enregistrée en mode brouillon avec succès', 'res' => $this->apiManager->save($form->getData())];
                return $this->view($formattedResult, Response::HTTP_CREATED);
            } else {
                $attribues = $furnitureService->getAttributesByDenominationIdAndFieldId($form->getData()->getDenomination()->getId(), $form->getData()->getField()->getId());
                if ((in_array('materialTechnique', $attribues) && !$form->getData()->getMaterialTechnique()) || (in_array('numberOfUnit', $attribues) && !$form->getData()->getNumberOfUnit())) {
                    $formattedResult = ['msg' => 'Notice enregistrée en mode brouillon avec succès', 'res' => $this->apiManager->save($form->getData())];
                    return $this->view($formattedResult, Response::HTTP_CREATED);
                } else {
                    $formattedResult = ['msg' => 'Notice enregistrée avec succès', 'res' => $this->apiManager->save($form->getData())];
                    return $this->view($formattedResult, Response::HTTP_CREATED);
                }
            }
        } else {
            throw new FormValidationException($form);
        }
    }
}
