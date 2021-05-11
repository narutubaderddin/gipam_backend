<?php


namespace App\Controller\API;


use App\Entity\ReportModel;
use App\Exception\FormValidationException;
use App\Form\ReportModelType;
use App\Model\ApiResponse;
use App\Services\ApiManager;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Swagger\Annotations as SWG;
use FOS\RestBundle\View\View;

/**
 * Class FieldController
 * @package App\Controller\API
 * @Route("/reportModels")
 */
class ReportModelController extends  AbstractFOSRestController
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
     *     description="Returns Report model by id",
     *     @SWG\Schema(
     *         ref=@Model(type=ReportModel::class, groups={"report_model", "id"})
     *     )
     * )
     * @SWG\Tag(name="ReportModels")
     * @Rest\View(serializerGroups={"report_model", "id"})
     *
     * @param ReportModel $reportModel
     *
     * @return View
     */
    public function showReportModel(ReportModel $reportModel)
    {
        return $this->view($reportModel, Response::HTTP_OK);
    }
    /**
     * @Rest\Get("/")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of report models",
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
     * @SWG\Tag(name="ReportModels")
     *
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="page number.")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="0", description="page size.")
     * @Rest\QueryParam(name="sort_by", nullable=true, default="id", description="order by")
     * @Rest\QueryParam(name="sort", requirements="(asc|desc)", nullable=true, default="asc", description="tri order asc|desc")
     * @Rest\QueryParam(name="name", map=true, nullable=false, description="filter by name. example: name[eq]=value")
     * @Rest\QueryParam(name="active" ,map=true, nullable=false, description="filter by active. example: active[eq]=1")
     * @Rest\QueryParam(name="search", map=false, nullable=true, description="search. example: search=text")
     * 
     * @Rest\View(serializerGroups={"response", "short", "report_model"})
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return View
     */
    public function ListReportModel(ParamFetcherInterface $paramFetcher)
    {
        $records = $this->apiManager->findRecordsByEntityName(ReportModel::class, $paramFetcher);
        return $this->view($records, Response::HTTP_OK);
    }
    /**
     * @Rest\Post("/")
     *
     * @SWG\Response(
     *     response=201,
     *     description="Returns created Report model",
     *     @SWG\Schema(
     *         ref=@Model(type=ReportModel::class, groups={"report_model", "id"})
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Creation error"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Add Report model",
     *     @Model(type=ReportModel::class, groups={"report_model"})
     * )
     * @SWG\Tag(name="ReportModels")
     *
     * @Rest\View(serializerGroups={"report_model", "id"})
     *
     * @param Request $request
     * @return View
     */
    public function postReportModel(Request $request)
    {
        $form = $this->createForm(ReportModelType::class);
        $form->submit($request->request->all());
        if ($form->isValid()) {
            $reportModel = $this->apiManager->save($form->getData());
            return $this->view($reportModel, Response::HTTP_CREATED);
        }
        throw new FormValidationException($form);
    }
    /**
     * @Rest\Put("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=204,
     *     description="Report model is updated"
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Update error"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Report model not found"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Update an Report model",
     *     @Model(type=ReportModel::class, groups={"report_model"})
     * )
     * @SWG\Tag(name="ReportModels")
     *
     * @Rest\View()
     *
     * @param Request $request
     * @param ReportModel $reportModel
     * @return View
     */
    public function updateReportModel(Request $request, ReportModel $reportModel)
    {
        $form = $this->createForm(ReportModelType::class, $reportModel);
        $form->submit($request->request->all(), false);
        if ($form->isValid()) {
            $this->apiManager->save($reportModel);
            return $this->view(null, Response::HTTP_NO_CONTENT);
        }
        throw new FormValidationException($form);
    }
    /**
     * @Rest\Delete("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=204,
     *     description="Report model is removed"
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Deleting errors"
     *     )
     * )
     * @SWG\Tag(name="ReportModels")
     *
     * @Rest\View()
     *
     * @param ReportModel $reportModel
     *
     * @return View
     */
    public function removeReportModel(ReportModel $reportModel)
    {
        $this->apiManager->delete($reportModel);
        return $this->view(null, Response::HTTP_NO_CONTENT);
    }

}