<?php

namespace App\Controller\API;

use App\Entity\MovementActionType;
use App\Exception\FormValidationException;
use App\Form\MovementActionTypeType;
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
 * Class MovementActionTypeController
 * @package App\Controller\API
 * @Route("/movementActionTypes")
 */
class MovementActionTypeController extends AbstractFOSRestController
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
     *     description="Returns Movement Action Type by id",
     *     @SWG\Schema(
     *         ref=@Model(type=MovementActionType::class, groups={"movement_action_type", "id"})
     *     )
     * )
     * @SWG\Tag(name="movementActionTypes")
     * @Rest\View(serializerGroups={"movement_action_type", "id"})
     *
     * @param MovementActionType $movementActionType
     *
     * @return View
     */
    public function showMovementActionType(MovementActionType $movementActionType)
    {
        return $this->view($movementActionType, Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of Movement Action Types",
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
     * @SWG\Tag(name="movementActionTypes")
     *
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="page number.")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="20", description="page size.")
     * @Rest\QueryParam(name="sort_by", nullable=true, default="id", description="order by")
     * @Rest\QueryParam(
     *     name="sort", requirements="(asc|desc)",
     *      nullable=true, default="asc",
     *      description="sorting order asc|desc"
     * )
     *
     * @Rest\QueryParam(name="label",map=true, nullable=false, description="filter by label. example: label[eq]=value")
     * @Rest\QueryParam(name="movementType", nullable=false, description="filter by Movement Type id. example: movementType[eq]=value")
     *
     * @Rest\View()
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return View
     */
    public function listMovementActionTypes(ParamFetcherInterface $paramFetcher)
    {
        $records = $this->apiManager->findRecordsByEntityName(MovementActionType::class, $paramFetcher);
        return $this->view($records, Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/")
     *
     * @SWG\Response(
     *     response=201,
     *     description="Returns created Movement Action Type",
     *     @SWG\Schema(
     *         ref=@Model(type=MovementActionType::class, groups={"movement_action_type", "id"})
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Creation error"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Add Movement Action Type",
     *     @Model(type=MovementActionType::class, groups={"movement_action_type"})
     * )
     * @SWG\Tag(name="movementActionTypes")
     *
     * @Rest\View(serializerGroups={"movement_action_type", "id", "errors"})
     *
     * @param Request $request
     * @return View
     */
    public function postMovementActionType(Request $request)
    {
        $form = $this->createForm(MovementActionTypeType::class);
        $form->submit($request->request->all());
        if ($form->isValid()) {
            $movementActionType = $this->apiManager->save($form->getData());
            return $this->view($movementActionType, Response::HTTP_CREATED);
        }
        throw new FormValidationException($form);
    }

    /**
     * @Rest\Put("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=204,
     *     description="Movement Action Type is updated"
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Update error"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Movement Action Type not found"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Update a Movement Action Type",
     *     @Model(type=MovementActionType::class, groups={"movement_action_type"})
     * )
     * @SWG\Tag(name="movementActionTypes")
     *
     * @Rest\View()
     *
     * @param Request $request
     * @param MovementActionType $movementActionType
     * @return View
     */
    public function updateMovementActionType(Request $request, MovementActionType $movementActionType)
    {
        $form = $this->createForm(MovementActionTypeType::class, $movementActionType);
        $form->submit($request->request->all(), false);
        if ($form->isValid()) {
            $this->apiManager->save($movementActionType);
            return $this->view(null, Response::HTTP_NO_CONTENT);
        }
        throw new FormValidationException($form);
    }

    /**
     * @Rest\Delete("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=204,
     *     description="Movement Action Type is removed"
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Deleting errors"
     *     )
     * )
     * @SWG\Tag(name="movementActionTypes")
     *
     * @Rest\View()
     *
     * @param MovementActionType $movementActionType
     *
     * @return View
     */
    public function removeMovementActionType(MovementActionType $movementActionType)
    {
        $this->apiManager->delete($movementActionType);
        return $this->view(null, Response::HTTP_NO_CONTENT);
    }
}