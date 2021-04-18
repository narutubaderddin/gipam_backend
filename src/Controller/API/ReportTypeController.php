<?php

namespace App\Controller\API;

use App\Entity\ReportType;
use App\Model\ApiResponse;
use App\Services\ApiManager;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Response;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Class ReportTypeController
 * @package App\Controller\API
 * @Route("/reportTypes")
 */
class ReportTypeController extends AbstractFOSRestController
{
    use ValidatorTrait;

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
     *     description="Returns ReportType by id",
     *     @SWG\Schema(
     *         ref=@Model(type=ReportType::class, groups={"report_type", "id"})
     *     )
     * )
     * @SWG\Tag(name="reportTypes")
     * @Rest\View(serializerGroups={"report_type", "id"})
     *
     * @param ReportType $reportType
     *
     * @return View
     */
    public function showReportType(ReportType $reportType)
    {
        return $this->view($reportType, Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of Report Types",
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
     *     name="label",
     *     in="query",
     *     type="string",
     *     description="The field used to filter by label"
     * )
     * @SWG\Tag(name="reportTypes")
     *
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="page number.")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="20", description="page size.")
     * @Rest\QueryParam(name="sort_by", nullable=true, default="id", description="order by")
     * @Rest\QueryParam(
     *     name="sort", requirements="(asc|desc)",
     *      nullable=true, default="asc",
     *      description="sorting order asc|desc"
     * )
     * @Rest\QueryParam(name="label",map=true, nullable=false, description="filter by label. example: label[eq]=value")
     * @Rest\QueryParam(name="active", nullable=false, description="filter by active. example: active[eq]=1")
     * @Rest\QueryParam(name="reportType", nullable=false, description="filter by report type. example: field[eq]=1")
     *
     * @Rest\View()
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return View
     */
    public function listReportTypes(ParamFetcherInterface $paramFetcher)
    {
        $records = $this->apiManager->findRecordsByEntityName(ReportType::class, $paramFetcher);
        return $this->view($records, Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/")
     * @ParamConverter("reportType", converter="fos_rest.request_body")
     * @SWG\Response(
     *     response=201,
     *     description="Returns created ReportType",
     *     @SWG\Schema(
     *         ref=@Model(type=ReportType::class, groups={"report_type", "id"})
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Creation error"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Add ReportType",
     *     @Model(type=ReportType::class, groups={"report_type"})
     * )
     * @SWG\Tag(name="reportTypes")
     *
     * @Rest\View(serializerGroups={"report_type", "id"})
     *
     * @param ReportType $reportType
     * @return View
     */
    public function postReportType(ReportType $reportType)
    {
        $errors = [];
        if ($this->isValid($reportType, $errors)) {
            $field = $this->apiManager->save($reportType);
            return $this->view($field, Response::HTTP_CREATED);
        }
        return $this->view($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Rest\Put("/")
     * @ParamConverter("reportType", converter="fos_rest.request_body")
     * @SWG\Response(
     *     response=204,
     *     description="Report Type is updated"
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Update error"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Report Type not found"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Update a ReportType",
     *     @Model(type=ReportType::class, groups={"report_type", "id"})
     * )
     * @SWG\Tag(name="reportTypes")
     *
     * @Rest\View()
     *
     * @param ReportType|null $reportType
     *
     * @return View
     */
    public function updateReportType(?ReportType $reportType)
    {
        $errors = [];
        if ($reportType) {
            if ($this->isValid($reportType, $errors)) {
                $this->apiManager->save($reportType);
                return $this->view(null, Response::HTTP_NO_CONTENT);
            }
            return $this->view($errors, Response::HTTP_BAD_REQUEST);
        }
        return $this->view("Report Type not found", Response::HTTP_NOT_FOUND);
    }

    /**
     * @Rest\Delete("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=204,
     *     description="Report Type is removed"
     *     )
     * )
     * @SWG\Tag(name="reportTypes")
     *
     * @Rest\View()
     *
     * @param ReportType $reportType
     *
     * @return View
     */
    public function removeReportType(ReportType $reportType)
    {
        $this->apiManager->delete($reportType);
        return $this->view(null, Response::HTTP_NO_CONTENT);
    }
}
