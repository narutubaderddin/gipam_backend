<?php

namespace App\Controller\API;

use App\Entity\Site;
use App\Exception\FormValidationException;
use App\Form\SiteType;
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

/**
 * Class SiteController
 * @package App\Controller\API
 * @Route("/sites")
 */
class SiteController extends AbstractFOSRestController
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
     * @Rest\Get("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns Site by id",
     *     @SWG\Schema(
     *         ref=@Model(type=Site::class, groups={"site"})
     *     )
     * )
     * @SWG\Tag(name="sites")
     * @Rest\View(serializerGroups={"site"})
     *
     * @param Site $site
     *
     * @return Response
     */
    public function showSite(Site $site)
    {
        return $this->view($site, Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of an Site",
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
     * @SWG\Tag(name="sites")
     *
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="page number.")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="0", description="page size.")
     * @Rest\QueryParam(name="sort_by", nullable=true, default="id", description="order by")
     * @Rest\QueryParam(name="sort", requirements="(asc|desc)", nullable=true, default="asc", description="tri order asc|desc")
     * @Rest\QueryParam(name="label", map=true, nullable=false, description="filter by name. example: label[eq]=value")
     * @Rest\QueryParam(name="startDate", map=true, nullable=false, description="filter by startDate. example: startDate[lt]=value")
     * @Rest\QueryParam(name="disappearanceDate", map=true, nullable=false, description="filter by disappearanceDate. example: disappearanceDate[lt]=value")
     * @Rest\QueryParam(name="search", map=false, nullable=true, description="search. example: search=text")
     *
     * @Rest\View()
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return View
     */
    public function listSites(ParamFetcherInterface $paramFetcher, Request $request)
    {
        $serializerGroups = $request->get('serializer_group', '["site"]');
        $serializerGroups = json_decode($serializerGroups, true);
        $serializerGroups[] = "response";
        $context = new Context();
        $context->setGroups($serializerGroups);
        $records = $this->apiManager->findRecordsByEntityName(Site::class, $paramFetcher);
        return $this->view($records, Response::HTTP_OK)->setContext($context);
    }
    /**
     *
     * @Rest\Get("/findByCriteria")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of sites filtred ",
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
     *     name="batiment",
     *     type="string",
     *     in="query",
     *     description="batiment id"
     * )
     *
     * @SWG\Tag(name="sites")
     *
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="page number.")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="0", description="page size.")
     * @Rest\QueryParam(name="region", nullable=true, default="", description="region id")
     * @Rest\QueryParam(name="departement", nullable=true, default="", description="epartement id")
     * @Rest\QueryParam(name="commune", nullable=true, default="", description="commune id")
     * @Rest\QueryParam(name="batiment", nullable=true, default="", description="commune id")
     * @Rest\QueryParam(name="search", nullable=true, default="", description="commune name")
     * @Rest\View()
     *
     * @param ParamFetcherInterface $paramFetcher
     * @param ApiManager $apiManager
     * @return View
     */
    public function listBuildingByCriteria(ParamFetcherInterface $paramFetcher,ApiManager $apiManager){
        $records =$apiManager->findRecordsByEntityNameAndCriteria(Site::class,$paramFetcher);
        return $this->view($records, Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/")
     *
     * @SWG\Response(
     *     response=201,
     *     description="Returns created Site",
     *     @SWG\Schema(
     *         ref=@Model(type=Site::class, groups={"site"})
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Creation error"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Add Site",
     *     @Model(type=Site::class, groups={"site"})
     * )
     * @SWG\Tag(name="sites")
     *
     * @Rest\View(serializerGroups={"site"})
     *
     * @param Request $request
     *
     * @return View
     *
     */
    public function postSite(Request $request)
    {
        $form = $this->createForm(SiteType::class);
        $form->submit($request->request->all());
        if ($form->isValid()) {
            $site = $this->apiManager->save($form->getData());
            return $this->view($site, Response::HTTP_CREATED);
        } else {
            throw new FormValidationException($form);
        }
    }

    /**
     * @Rest\Put("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=204,
     *     description="Site is updated"
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Update error"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Update a Site",
     *     @Model(type=Site::class, groups={"site"})
     * )
     * @SWG\Tag(name="sites")
     *
     * @Rest\View()
     *
     * @param Request $request
     * @param Site $site
     *
     * @return View
     *
     */
    public function updateSite(Request $request, Site $site)
    {
        $form = $this->createForm(SiteType::class, $site);
        $form->submit($request->request->all(), false);

        if ($form->isValid()) {
            $this->apiManager->save($site);
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
     *     description="Site is removed"
     *     )
     * )
     * @SWG\Tag(name="sites")
     *
     * @Rest\View()
     *
     * @param Site $site
     *
     * @return View
     */
    public function removeSite(Site $site)
    {
        $this->apiManager->delete($site);
        return $this->view(null, Response::HTTP_NO_CONTENT);
    }
}