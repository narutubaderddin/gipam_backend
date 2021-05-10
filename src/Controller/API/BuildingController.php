<?php

namespace App\Controller\API;

use App\Entity\Building;
use App\Exception\FormValidationException;
use App\Form\BuildingType;
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
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class BuildingController
 * @package App\Controller\API
 * @Route("/buildings")
 */
class BuildingController extends AbstractFOSRestController
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
     *     description="Returns Building by id",
     *     @SWG\Schema(
     *         ref=@Model(type=Building::class, groups={"building", "id"})
     *     )
     * )
     * @SWG\Tag(name="buildings")
     * @Rest\View(serializerGroups={"building", "id"})
     *
     * @param Building $building
     *
     * @return View
     */
    public function showBuilding(Building $building)
    {
        return $this->view($building, Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of buildings",
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
     * @SWG\Tag(name="buildings")
     *
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="page number.")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="0", description="page size.")
     * @Rest\QueryParam(name="sort_by", nullable=true, default="id", description="order by")
     * @Rest\QueryParam(
     *     name="sort", requirements="(asc|desc)",
     *      nullable=true, default="asc",
     *      description="sorting order asc|desc"
     * )
     * @Rest\QueryParam(name="name", map=true, nullable=false, description="filter by name. example: name[eq]=value")
     * @Rest\QueryParam(name="address", map=true, nullable=false, description="filter by address. example: address[eq]=value")
     * @Rest\QueryParam(name="distrib", map=true, nullable=false, description="filter by distrib. example: distrib[eq]=value")
     * @Rest\QueryParam(name="cedex", map=true, nullable=false, description="filter by cedex. example: cedex[eq]=value")
     * @Rest\QueryParam(name="site", nullable=false, description="filter by site id. example: site[eq]=value")
     * @Rest\QueryParam(name="commune_id", map=true, nullable=false, description="filter by commune id. example: commune_id[eq]=value")
     * @Rest\QueryParam(name="commune_name", map=true, nullable=false, description="filter by commune name. example: commune_name[eq]=value")
     * @Rest\QueryParam(name="search", map=false, nullable=true, description="search. example: search=text")
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
    public function listBuilding(ParamFetcherInterface $paramFetcher)
    {
        $records = $this->apiManager->findRecordsByEntityName(Building::class, $paramFetcher);
        return $this->view($records, Response::HTTP_OK);
    }
    /**
     *
     * @Rest\Get("/findByCriteria")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of an building filtred ",
     *     @SWG\Schema(
     *         @SWG\Items(ref=@Model(type=ApiResponse::class))
     *     )
     * )
     *
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
     *
     * @SWG\Parameter(
     *     name="departement",
     *     in="query",
     *     type="string",
     *     description="departements id"
     * )
     * @SWG\Parameter(
     *     name="region",
     *      type="string",
     *     in="query",
     *     description="regions id"
     * )
     * @SWG\Parameter(
     *     name="commune",
     *     type="string",
     *     in="query",
     *     description="commune id"
     * )
     * @SWG\Parameter(
     *     name="search",
     *     type="string",
     *      in="query",
     *     description="building name"
     * )
     * @SWG\Tag(name="buildings")
     *
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="page number.")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="0", description="page size.")
     * @Rest\QueryParam(name="region", nullable=true, default="", description="region id")
     * @Rest\QueryParam(name="departement", nullable=true, default="", description="epartement id")
     * @Rest\QueryParam(name="commune", nullable=true, default="", description="commune id")
     * @Rest\QueryParam(name="search", nullable=true, default="", description="building name")
     * @Rest\View()
     *
     * @param ParamFetcherInterface $paramFetcher
     * @param ApiManager $apiManager
     * @return View
     */
    public function listBuildingByCriteria(ParamFetcherInterface $paramFetcher,ApiManager $apiManager){
        $records =$apiManager->findRecordsByEntityNameAndCriteria(Building::class,$paramFetcher);
        return $this->view($records, Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/")
     *
     * @SWG\Response(
     *     response=201,
     *     description="Returns created Building",
     *     @SWG\Schema(
     *         ref=@Model(type=Building::class, groups={"building", "id"})
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Creation error"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Add Building",
     *     @Model(type=Building::class, groups={"building"})
     * )
     * @SWG\Tag(name="buildings")
     *
     * @Rest\View(serializerGroups={"building", "id"})
     *
     * @param Request $request
     * @return View
     */
    public function postBuilding(Request $request)
    {
        $form = $this->createForm(BuildingType::class);
        $form->submit($request->request->all());
        if ($form->isValid()) {
            $building = $this->apiManager->save($form->getData());
            return $this->view($building, Response::HTTP_CREATED);
        }
        throw new FormValidationException($form);
    }

    /**
     * @Rest\Put("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=204,
     *     description="Building is updated"
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Update error"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Building not found"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Update a Building",
     *     @Model(type=Building::class, groups={"building"})
     * )
     * @SWG\Tag(name="buildings")
     *
     * @Rest\View()
     *
     * @param Request $request
     * @param Building $building
     * @return View
     */
    public function updateBuilding(Request $request, Building $building)
    {
        $form = $this->createForm(BuildingType::class, $building);
        $form->submit($request->request->all(), false);
        if ($form->isValid()) {
            $this->apiManager->save($building);
            return $this->view(null, Response::HTTP_NO_CONTENT);
        }
        throw new FormValidationException($form);
    }

    /**
     * @Rest\Delete("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=204,
     *     description="Building is removed"
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Deleting errors"
     *     )
     * )
     * @SWG\Tag(name="buildings")
     *
     * @Rest\View()
     *
     * @param Building $building
     *
     * @return View
     */
    public function removeBuilding(Building $building)
    {
        $this->apiManager->delete($building);
        return $this->view(null, Response::HTTP_NO_CONTENT);
    }
}
