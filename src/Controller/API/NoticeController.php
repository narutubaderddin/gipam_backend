<?php

namespace App\Controller\API;

use App\Entity\ArtWork;
use App\Exception\FormValidationException;
use App\Form\ArtWorkType;
use App\Services\ApiManager;
use App\Services\FurnitureService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
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
     * @SWG\Tag(name="notice")
     *
     * @Rest\QueryParam(name="denomination_id", requirements="\d+", nullable=true, description="id denomination")
     * @Rest\QueryParam(name="field_id", requirements="\d+", nullable=true, description="id field")
     * @Rest\View()
     *
     * @param ParamFetcherInterface $paramFetcher
     * @param FurnitureService $furnitureService
     *
     * @return Response
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
     * @SWG\Tag(name="notice")
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
     * @return Response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createDepositNotice(Request $request)
    {
        $form = $this->createForm(ArtWorkType::class, null, [
            'status' => ArtWorkType::DEPOSIT_STATUS]
        );
        $form->submit($this->apiManager->getPostDataFromRequest($request));

        if ($form->isSubmitted() && $form->isValid()) {
            $artWork = $this->apiManager->save($form->getData());
            return $this->view($artWork, Response::HTTP_CREATED);
        } else {
            throw new FormValidationException($form);
        }
    }
}