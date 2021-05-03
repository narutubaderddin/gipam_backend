<?php

namespace App\Controller\API;

use App\Entity\ActionReportType;
use App\Exception\FormValidationException;
use App\Form\ActionReportTypeType;
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
 * Class ActionReportTypeController
 * @package App\Controller\API
 * @Route("/actionReportTypes")
 */
class ActionReportTypeController extends AbstractFOSRestController
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
     *     description="Returns Action Report Type by id",
     *     @SWG\Schema(
     *         ref=@Model(type=ActionReportType::class, groups={"action_type", "id"})
     *     )
     * )
     * @SWG\Tag(name="actionReportTypes")
     * @Rest\View(serializerGroups={"action_type", "id"})
     *
     * @param ActionReportType $actionReportType
     *
     * @return View
     */
    public function showActionReportType(ActionReportType $actionReportType)
    {
        return $this->view($actionReportType, Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of Action Report Types",
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
     * @SWG\Tag(name="actionReportTypes")
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
     * @Rest\QueryParam(name="label",map=true, nullable=false, description="filter by label. example: label[eq]=value")
     * @Rest\QueryParam(name="search", map=false, nullable=true, description="search. example: search=text")
     *
     * @Rest\View()
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return View
     */
    public function listActionReportTypes(ParamFetcherInterface $paramFetcher)
    {
        $records = $this->apiManager->findRecordsByEntityName(ActionReportType::class, $paramFetcher);
        return $this->view($records, Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/")
     *
     * @SWG\Response(
     *     response=201,
     *     description="Returns created Action Type",
     *     @SWG\Schema(
     *         ref=@Model(type=ActionReportType::class, groups={"action_type", "id"})
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Creation error"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Add Action Report Type",
     *     @Model(type=ActionReportType::class, groups={"action_type"})
     * )
     * @SWG\Tag(name="actionReportTypes")
     *
     * @Rest\View(serializerGroups={"action_type", "id"})
     *
     * @param Request $request
     * @return View
     */
    public function postActionReportType(Request $request)
    {
        $form = $this->createForm(ActionReportTypeType::class);
        $form->submit($request->request->all());
        if ($form->isValid()) {
            $actionReportType = $this->apiManager->save($form->getData());
            return $this->view($actionReportType, Response::HTTP_CREATED);
        }
        throw new FormValidationException($form);
    }

    /**
     * @Rest\Put("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=204,
     *     description="Action Report Type is updated"
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Update error"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Action Type not found"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Update an Action Report Type",
     *     @Model(type=ActionReportType::class, groups={"action_type"})
     * )
     * @SWG\Tag(name="actionReportTypes")
     *
     * @Rest\View()
     *
     * @param Request $request
     * @param ActionReportType $actionReportType
     * @return View
     */
    public function updateActionReportType(Request $request, ActionReportType $actionReportType)
    {
        $form = $this->createForm(ActionReportTypeType::class, $actionReportType);
        $form->submit($request->request->all(), false);
        if ($form->isValid()) {
            $this->apiManager->save($actionReportType);
            return $this->view(null, Response::HTTP_NO_CONTENT);
        }
        throw new FormValidationException($form);
    }

    /**
     * @Rest\Delete("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=204,
     *     description="Action Report Type is removed"
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Deleting errors"
     *     )
     * )
     * @SWG\Tag(name="actionReportTypes")
     *
     * @Rest\View()
     *
     * @param ActionReportType $actionReportType
     *
     * @return View
     */
    public function removeActionReportType(ActionReportType $actionReportType)
    {
        $this->apiManager->delete($actionReportType);
        return $this->view(null, Response::HTTP_NO_CONTENT);
    }
}
