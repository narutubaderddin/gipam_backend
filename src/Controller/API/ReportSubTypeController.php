<?php

namespace App\Controller\API;

use App\Entity\ReportSubType;
use App\Entity\ReportType;
use App\Form\ReportSubTypeType;
use App\Form\ReportTypeType;
use App\Model\ApiResponse;
use App\Model\FormError;
use App\Services\ApiManager;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View;
use JMS\Serializer\SerializationContext;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class ReportTypeController
 * @package App\Controller\API
 * @Route("/reportSubTypes")
 */
class ReportSubTypeController extends AbstractFOSRestController
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
     *     description="Returns Report Subtype by id",
     *     @SWG\Schema(
     *         ref=@Model(type=ReportSubType::class, groups={"report_sub_type", "id"})
     *     )
     * )
     * @SWG\Tag(name="reportSubTypes")
     * @Rest\View(serializerGroups={"report_sub_type", "id"})
     *
     * @param ReportSubType $reportSubType
     *
     * @return View
     */
    public function showReportSubType(ReportSubType $reportSubType)
    {
        return $this->view($reportSubType, Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of Report Subtypes",
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
     * @SWG\Tag(name="reportSubTypes")
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
     * @Rest\QueryParam(name="reportType", nullable=false, description="filter by report type id. example: reportType[eq]=1")
     *
     * @Rest\View()
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return View
     */
    public function listReportSubTypes(ParamFetcherInterface $paramFetcher)
    {
        $records = $this->apiManager->findRecordsByEntityName(ReportSubType::class, $paramFetcher);
        return $this->view($records, Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/")
     *
     * @SWG\Response(
     *     response=201,
     *     description="Returns created Report Subtype",
     *     @SWG\Schema(
     *         ref=@Model(type=ReportSubType::class, groups={"report_sub_type"})
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Creation error"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Add Report Subtype",
     *     @Model(type=ReportSubType::class, groups={"report_sub_type"})
     * )
     * @SWG\Tag(name="reportSubTypes")
     *
     * @Rest\View(serializerGroups={"report_sub_type", "id", "errors"})
     *
     * @param Request $request
     * @return View
     */
    public function postReportSubType(Request $request)
    {
        $form = $this->createForm(ReportSubTypeType::class);
        $form->submit($request->request->all());
        if ($form->isValid()) {
            $reportType = $this->apiManager->save($form->getData());
            return $this->view($reportType, Response::HTTP_CREATED);
        }
        return $this->view(new FormError($form), Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Rest\Put("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=204,
     *     description="Report Subtype is updated"
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Update error"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Report Subtype not found"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Update a Report SubType",
     *     @Model(type=ReportSubType::class, groups={"report_sub_type"})
     * )
     * @SWG\Tag(name="reportSubTypes")
     *
     * @Rest\View(serializerGroups={"errors"})
     *
     * @param Request $request
     * @param ReportSubType $reportSubType
     * @return View
     */
    public function updateReportSubType(Request $request, ReportSubType $reportSubType)
    {
        $form = $this->createForm(ReportSubTypeType::class, $reportSubType);
        $form->submit($request->request->all(), false);
        if ($form->isValid()) {
            $this->apiManager->save($reportSubType);
            return $this->view(null, Response::HTTP_NO_CONTENT);
        }
        return $this->view(new FormError($form), Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Rest\Delete("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=204,
     *     description="Report Subtype is removed"
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Deleting errors"
     *     )
     * )
     * @SWG\Tag(name="reportSubTypes")
     *
     * @Rest\View()
     *
     * @param ReportSubType $reportSubType
     * @return View
     */
    public function removeReportSubType(ReportSubType $reportSubType)
    {
        if ($reportSubType->getReports()->isEmpty()) {
            $this->apiManager->delete($reportSubType);
            return $this->view(null, Response::HTTP_NO_CONTENT);
        }
        return $this->view("Report SubType has related Reports", Response::HTTP_BAD_REQUEST);
    }
}
