<?php

namespace App\Controller\API;

use App\Entity\ReportType;
use App\Exception\FormValidationException;
use App\Form\ReportTypeType;
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
 * Class ReportTypeController
 * @package App\Controller\API
 * @Route("/reportTypes")
 */
class ReportTypeController extends AbstractFOSRestController
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
     *
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
     * @param Request $request
     * @return View
     */
    public function postReportType(Request $request)
    {
        $form = $this->createForm(ReportTypeType::class);
        $form->submit($request->request->all());
        if ($form->isValid()) {
            $reportType = $this->apiManager->save($form->getData());
            return $this->view($reportType, Response::HTTP_CREATED);
        }
        throw new FormValidationException($form);
    }

    /**
     * @Rest\Put("/{id}", requirements={"id"="\d+"})
     *
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
     *     @Model(type=ReportType::class, groups={"report_type"})
     * )
     * @SWG\Tag(name="reportTypes")
     *
     * @Rest\View()
     *
     * @param Request $request
     * @param ReportType $reportType
     * @return View
     */
    public function updateReportType(Request $request, ReportType $reportType)
    {
        $form = $this->createForm(ReportTypeType::class, $reportType);
        $form->submit($request->request->all(), false);
        if ($form->isValid()) {
            $this->apiManager->save($reportType);
            return $this->view(null, Response::HTTP_NO_CONTENT);
        }
        throw new FormValidationException($form);
    }

    /**
     * @Rest\Delete("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=204,
     *     description="Report Type is removed"
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Deleting errors"
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