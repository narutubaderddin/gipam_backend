<?php

namespace App\Controller\API;

use App\Entity\Commune;
use App\Exception\FormValidationException;
use App\Form\CommuneType;
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
 * Class CommuneController
 * @package App\Controller\API
 * @Route("/communes")
 */
class CommuneController extends AbstractFOSRestController
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
    )
    {
        $this->apiManager = $apiManager;
        $this->validator = $validator;
    }

    /**
     * @Rest\Get("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns Commune by id",
     *     @SWG\Schema(
     *         ref=@Model(type=Commune::class, groups={"commune", "id"})
     *     )
     * )
     * @SWG\Tag(name="communes")
     * @Rest\View(serializerGroups={"commune", "id"})
     *
     * @param Commune $commune
     *
     * @return View
     */
    public function showCommune(Commune $commune)
    {
        return $this->view($commune, Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of communes",
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
     * @SWG\Tag(name="communes")
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
     * @param Request $request
     * @return View
     */
    public function listCommune(ParamFetcherInterface $paramFetcher, Request $request)
    {
        $serializerGroups = $request->get('serializer_group') ?? null;
        if ($serializerGroups) {
            $serializerGroups = json_decode($serializerGroups, true);
            $context = new Context();
            $context->setGroups($serializerGroups);
            $records = $this->apiManager->findRecordsByEntityName(Commune::class, $paramFetcher);
            return $this->view($records, Response::HTTP_OK)->setContext($context);
        }
        $records = $this->apiManager->findRecordsByEntityName(Commune::class, $paramFetcher);
        return $this->view($records, Response::HTTP_OK);
    }
    /**
     *
     * @Rest\Get("/findByCriteria")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of an comunes filtred ",
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
     *      type="string",
     *     description="departements id"
     * )
     * @SWG\Parameter(
     *     name="region",
     *      in="query",
     *      type="string",
     *     description="regions id"
     * )
     * @SWG\Parameter(
     *     name="search",
     *     type="string",
     *      in="query",
     *     description="commune name"
     * )
     *
     * @SWG\Tag(name="communes")
     *
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="page number.")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="0", description="page size.")
     * @Rest\QueryParam(name="region", nullable=true, default="", description="region id")
     * @Rest\QueryParam(name="departement", nullable=true, default="", description="epartement id")
     * @Rest\QueryParam(name="search", nullable=true, default="", description="commune name")
     *
     * @Rest\View()
     *
     * @param ParamFetcherInterface $paramFetcher
     * @param ApiManager $apiManager
     * @return View
     */
    public function listCommuneByCriteria(ParamFetcherInterface $paramFetcher,ApiManager $apiManager){

        $records =$apiManager->findRecordsByEntityNameAndCriteria(Commune::class,$paramFetcher);
        return $this->view($records, Response::HTTP_OK);
    }
    /**
     * @Rest\Post("/")
     *
     * @SWG\Response(
     *     response=201,
     *     description="Returns created Commune",
     *     @SWG\Schema(
     *         ref=@Model(type=Commune::class, groups={"commune", "id"})
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Creation error"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Add Commune",
     *     @Model(type=Commune::class, groups={"commune"})
     * )
     * @SWG\Tag(name="communes")
     *
     * @Rest\View(serializerGroups={"commune", "id"})
     *
     * @param Request $request
     * @return View
     */
    public function postCommune(Request $request)
    {
        $form = $this->createForm(CommuneType::class);
        $form->submit($request->request->all());
        if ($form->isValid()) {
            $commune = $this->apiManager->save($form->getData());
            return $this->view($commune, Response::HTTP_CREATED);
        }
        throw new FormValidationException($form);
    }

    /**
     * @Rest\Put("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=204,
     *     description="Commune is updated"
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Update error"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Commune not found"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Update a Commune",
     *     @Model(type=Commune::class, groups={"commune"})
     * )
     * @SWG\Tag(name="communes")
     *
     * @Rest\View()
     *
     * @param Request $request
     * @param Commune $commune
     * @return View
     */
    public function updateCommune(Request $request, Commune $commune)
    {
        $form = $this->createForm(CommuneType::class, $commune);
        $form->submit($request->request->all(), false);
        if ($form->isValid()) {
            $this->apiManager->save($commune);
            return $this->view(null, Response::HTTP_NO_CONTENT);
        }
        throw new FormValidationException($form);
    }

    /**
     * @Rest\Delete("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=204,
     *     description="Commune is removed"
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Deleting errors"
     *     )
     * )
     * @SWG\Tag(name="communes")
     *
     * @Rest\View()
     *
     * @param Commune $commune
     *
     * @return View
     */
    public function removeCommune(Commune $commune)
    {
        $this->apiManager->delete($commune);
        return $this->view(null, Response::HTTP_NO_CONTENT);
    }
}
