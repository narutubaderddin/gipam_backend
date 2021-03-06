<?php

namespace App\Controller\API;

use App\Entity\Region;
use App\Exception\FormValidationException;
use App\Form\RegionType;
use App\Model\ApiResponse;
use App\Services\ApiManager;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class RegionController
 * @package App\Controller\API
 * @Route("/regions")
 */
class RegionController extends AbstractFOSRestController
{
    /**
     * @var ApiManager
     */
    protected $apiManager;

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    public function __construct(
        ApiManager $apiManager,
        ValidatorInterface $validator
    ) {
        $this->apiManager = $apiManager;
        $this->validator = $validator;
    }

    /**
     * @Rest\Get("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns Region by id",
     *     @SWG\Schema(
     *         ref=@Model(type=Region::class, groups={"region", "id"})
     *     )
     * )
     * @SWG\Tag(name="regions")
     * @Rest\View(serializerGroups={"region", "id"})
     *
     * @param Region $region
     *
     * @return View
     */
    public function showRegion(Region $region)
    {
        return $this->view($region, Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of Regions",
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
     * @SWG\Parameter(
     *     name="name",
     *     in="query",
     *     type="string",
     *     description="The field used to filter by name"
     * )
     * @SWG\Tag(name="regions")
     *
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="page number.")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="0", description="page size.")
     * @Rest\QueryParam(name="sort_by", nullable=true, default="id", description="order by")
     * @Rest\QueryParam(name="search", map=false, nullable=true, description="search. example: search=text")
     * @Rest\QueryParam(
     *     name="sort", requirements="(asc|desc)",
     *      nullable=true, default="asc",
     *      description="sorting order asc|desc"
     * )
     * @Rest\QueryParam(name="name", map=true, nullable=false, description="filter by name. example: name[eq]=value")
     * @Rest\QueryParam(name="startDate",
     *      map=true, nullable=false,
     *      description="filter by start date. example: startDate[eq]=value"
     * )
     * @Rest\QueryParam(name="disappearanceDate",
     *      map=true, nullable=false,
     *      description="filter by disappearance date. example: disappearanceDate[eq]=value"
     * )
     * @Rest\QueryParam(name="search", map=false, nullable=true, description="search. example: search=text")
     *
     * @Rest\View()
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return View
     */
    public function listregions(ParamFetcherInterface $paramFetcher, Request $request)
    {
        $serializerGroups = $request->get('serializer_group', '["region", "id", "short"]');
        $serializerGroups = json_decode($serializerGroups, true);
        $serializerGroups[] = "response";
        $context = new Context();
        $context->setGroups($serializerGroups);
        $records = $this->apiManager->findRecordsByEntityName(Region::class, $paramFetcher);
        return $this->view($records, Response::HTTP_OK)->setContext($context);
    }

    /**
     * @Rest\Post("/")
     *
     * @SWG\Response(
     *     response=201,
     *     description="Returns created Region",
     *     @SWG\Schema(
     *         ref=@Model(type=Region::class, groups={"region", "id"})
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Creation error"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Add Region",
     *     @Model(type=Region::class, groups={"region"})
     * )
     * @SWG\Tag(name="regions")
     *
     * @Rest\View(serializerGroups={"region", "id"})
     *
     * @param Request $request
     * @return View
     */
    public function postRegion(Request $request)
    {
        $form = $this->createForm(RegionType::class);
        $form->submit($request->request->all());
        if ($form->isValid()) {
            $region = $this->apiManager->save($form->getData());
            return $this->view($region, Response::HTTP_CREATED);
        }
        throw new FormValidationException($form);
    }

    /**
     * @Rest\Put("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=204,
     *     description="Region is updated"
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Update error"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Region not found"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Update a Region",
     *     @Model(type=Region::class, groups={"region"})
     * )
     * @SWG\Tag(name="regions")
     *
     * @Rest\View()
     *
     * @param Request $request
     * @param Region $region
     * @return View
     */
    public function updateRegion(Request $request, Region $region)
    {
        $form = $this->createForm(RegionType::class, $region);
        $form->submit($request->request->all(), false);
        if ($form->isValid()) {
            $this->apiManager->save($region);
            return $this->view(null, Response::HTTP_NO_CONTENT);
        }
        throw new FormValidationException($form);
    }

    /**
     * @Rest\Delete("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=204,
     *     description="Region is removed"
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Deleting errors"
     *     )
     * )
     * @SWG\Tag(name="regions")
     *
     * @Rest\View()
     *
     * @param Region $region
     *
     * @return View
     */
    public function removeRegion(Region $region)
    {
        $this->apiManager->delete($region);
        return $this->view(null, Response::HTTP_NO_CONTENT);
    }
}
