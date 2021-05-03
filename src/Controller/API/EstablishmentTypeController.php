<?php

namespace App\Controller\API;

use App\Entity\EstablishmentType;
use App\Exception\FormValidationException;
use App\Form\EstablishmentTypeType;
use App\Model\ApiResponse;
use App\Services\ApiManager;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Swagger\Annotations as SWG;

/**
 * Class EstablishmentTypeController
 * @package App\Controller\API
 * @Route("/establishmentTypes")
 */
class EstablishmentTypeController extends AbstractFOSRestController
{

    /**
     * @var ApiManager
     */
    protected $apiManager;

    public function __construct(
        ApiManager $apiManager
    ) {
        $this->apiManager = $apiManager;
    }

    /**
     * @Rest\Get("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns Establishment Type by id",
     *     @SWG\Schema(
     *         ref=@Model(type=EstablishmentType::class, groups={"establishmentType"})
     *     )
     * )
     * @SWG\Tag(name="establishmentTypes")
     * @Rest\View(serializerGroups={"establishment_type"})
     *
     * @param EstablishmentType $establishmentType
     *
     * @return View
     */
    public function showEstablishmentType(EstablishmentType $establishmentType)
    {
        return $this->view($establishmentType, Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of Establishment Types",
     *     @SWG\Schema(
     *         @SWG\Items(ref=@Model(type=ApiResponse::class))
     *     )
     * )
     * @SWG\Parameter(
     *     name="page",
     *     in="query",
     *     type="integer",
     *     description="The field used to page number"
     * )
     * @SWG\Parameter(
     *     name="limit",
     *     in="query",
     *     type="integer",
     *     description="The field used to page size"
     * )
     * @SWG\Parameter(
     *     name="sort_by",
     *     in="query",
     *     type="string",
     *     description="The field used to sort by"
     * )
     * @SWG\Parameter(
     *     name="sort",
     *     in="query",
     *     type="string",
     *     description="The field used to sort type"
     * )
     *
     * @SWG\Tag(name="establishmentTypes")
     *
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="page number.")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="0", description="page size.")
     * @Rest\QueryParam(name="sort_by", nullable=true, default="id", description="order by")
     * @Rest\QueryParam(name="sort", requirements="(asc|desc)", nullable=true, default="asc", description="tri order asc|desc")
     * @Rest\QueryParam(name="label", map=true, nullable=false, description="filter by label. example: label[eq]=value")
     * @Rest\QueryParam(name="search", map=false, nullable=true, description="search. example: search=text")
     *
     * @Rest\View()
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return View
     */
    public function listEstablishmentTypes(ParamFetcherInterface $paramFetcher)
    {
        $records = $this->apiManager->findRecordsByEntityName(EstablishmentType::class, $paramFetcher);
        return $this->view($records, Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/")
     *
     * @SWG\Response(
     *     response=201,
     *     description="Returns created Establishment Type",
     *     @SWG\Schema(
     *         ref=@Model(type=EstablishmentType::class, groups={"establishment_type"})
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Creation error"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Add Establishment Type",
     *     @Model(type=EstablishmentType::class, groups={"establishment_type"})
     * )
     * @SWG\Tag(name="establishmentTypes")
     *
     * @Rest\View(serializerGroups={"establishment_type"})
     *
     * @param Request $request
     *
     * @return View
     *
     */
    public function postEstablishmentType(Request $request)
    {
        $form = $this->createForm(EstablishmentTypeType::class);
        $form->submit($request->request->all());
        if ($form->isValid()) {
            $establishmentType = $this->apiManager->save($form->getData());
            return $this->view($establishmentType, Response::HTTP_CREATED);
        } else {
            throw new FormValidationException($form);
        }
    }

    /**
     * @Rest\Put("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=204,
     *     description="EstablishmentType is updated"
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Update error"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Update a EstablishmentType",
     *     @Model(type=EstablishmentType::class, groups={"establishment_type"})
     * )
     * @SWG\Tag(name="establishmentTypes")
     *
     * @Rest\View()
     *
     * @param Request $request
     * @param EstablishmentType $establishmentType
     *
     * @return View
     *
     */
    public function updateEstablishmentType(Request $request, EstablishmentType $establishmentType)
    {
        $form = $this->createForm(EstablishmentTypeType::class, $establishmentType);
        $form->submit($request->request->all(), false);

        if ($form->isValid()) {
            $this->apiManager->save($establishmentType);
            return $this->view(null, Response::HTTP_NO_CONTENT);
        } else {
            throw new FormValidationException($form);
        }
    }

    /**
     * @Rest\Delete("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=204,
     *     description="EstablishmentType is removed"
     *     )
     * )
     * @SWG\Tag(name="establishmentTypes")
     *
     * @Rest\View()
     *
     * @param EstablishmentType $establishmentType
     *
     * @return View
     */
    public function removeEstablishmentType(EstablishmentType $establishmentType)
    {
        $this->apiManager->delete($establishmentType);
        return $this->view(null, Response::HTTP_NO_CONTENT);
    }
}