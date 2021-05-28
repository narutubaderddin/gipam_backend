<?php

namespace App\Controller\API;


use App\Entity\Report;
use App\Services\ApiManager;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;

/**
 * Class MovementController
 * @package App\Controller\API
 * @Route("/reports")
 */
class ReportController extends AbstractFOSRestController
{
    /**
     * @var ApiManager
     */
    protected $apiManager;

    public function __construct(ApiManager $apiManager){
        $this->apiManager = $apiManager;
    }

    /**
     * @Rest\Get("/")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of movements",
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
     * @SWG\Tag(name="reports")
     *
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="page number.")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="0", description="page size.")
     * @Rest\QueryParam(name="sort_by", nullable=true, default="id", description="order by")
     * @Rest\QueryParam(
     *     name="sort", requirements="(asc|desc)",
     *      nullable=true, default="asc",
     *      description="sorting order asc|desc"
     * )
     * @Rest\QueryParam(name="status", map=true, nullable=true, description="filter by status.")
     * @Rest\QueryParam(name="furniture", map=true, nullable=true, description="filter furniture.")
     * @Rest\QueryParam(name="actions", map=true, nullable=true, description="filter by actions.")
     * @Rest\QueryParam(name="reportSubType", map=true, nullable=true, description="filter by reportSubType.")
     * @Rest\QueryParam(name="collectionTitle", map=true, nullable=true, description="filter by collectionTitle")
     * @Rest\QueryParam(name="search", map=false, nullable=true, description="search. example: search=text")
     * @Rest\QueryParam(name="date",
     *      map=true, nullable=false,
     *      description="filter by start date. example: date[eq]=value"
     * )
     * @Rest\QueryParam(name="search", map=false, nullable=true, description="search. example: search=text")
     *
     * @Rest\View( serializerGroups={"report_list"},serializerEnableMaxDepthChecks=true)
     *
     * @param ParamFetcherInterface $paramFetcher
     * @param Request $request
     * @return View
     */
    public function listReports(ParamFetcherInterface $paramFetcher, Request $request)
    {
        $serializerGroups = $request->get('serializer_group', '["report", "id", "short"]');
        $serializerGroups = json_decode($serializerGroups, true);
        $serializerGroups[] = "response";
        $context = new Context();
        $context->setGroups($serializerGroups);
        $records = $this->apiManager->findRecordsByEntityName(Report::class, $paramFetcher);
        return $this->view($records, Response::HTTP_OK)->setContext($context);
    }
}
