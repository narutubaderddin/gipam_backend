<?php

namespace App\Controller\API;

use App\Entity\MovementType;
use App\Exception\FormValidationException;
use App\Form\MovementTypeType;
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
 * Class MovementTypeController
 * @package App\Controller\API
 * @Route("/movementTypes")
 */
class MovementTypeController extends AbstractFOSRestController
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
     *     description="Returns Movement Type by id",
     *     @SWG\Schema(
     *         ref=@Model(type=MovementType::class, groups={"movement_type", "id"})
     *     )
     * )
     * @SWG\Tag(name="movementTypes")
     * @Rest\View(serializerGroups={"movement_type", "id"})
     *
     * @param MovementType $movementType
     *
     * @return View
     */
    public function showMovementType(MovementType $movementType)
    {
        return $this->view($movementType, Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of Movement Types",
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
     * @SWG\Tag(name="movementTypes")
     *
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="page number.")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="0", description="page size.")
     * @Rest\QueryParam(name="sort_by", nullable=true, default="id", description="order by")
     * @Rest\QueryParam(
     *     name="sort", requirements="(asc|desc)",
     *      nullable=true, default="asc",
     *      description="sorting order asc|desc"
     * )
     * @Rest\QueryParam(name="label",map=true, nullable=false, description="filter by label. example: label[eq]=value")
     * @Rest\QueryParam(name="search", map=false, nullable=true, description="search. example: search=text")
     * @Rest\View()
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return View
     */
    public function listMovementTypes(ParamFetcherInterface $paramFetcher)
    {
        $records = $this->apiManager->findRecordsByEntityName(MovementType::class, $paramFetcher);
        return $this->view($records, Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/")
     *
     * @SWG\Response(
     *     response=201,
     *     description="Returns created Action Type",
     *     @SWG\Schema(
     *         ref=@Model(type=MovementType::class, groups={"movement_type", "id"})
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Creation error"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Add MovementType",
     *     @Model(type=MovementType::class, groups={"movement_type"})
     * )
     * @SWG\Tag(name="movementTypes")
     *
     * @Rest\View(serializerGroups={"movement_type", "id", "errors"})
     *
     * @param Request $request
     * @return View
     */
    public function postMovementType(Request $request)
    {
        $form = $this->createForm(MovementTypeType::class);
        $form->submit($request->request->all());
        if ($form->isValid()) {
            $movementType = $this->apiManager->save($form->getData());
            return $this->view($movementType, Response::HTTP_CREATED);
        }
        throw new FormValidationException($form);
    }

    /**
     * @Rest\Put("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=204,
     *     description="Movement Type is updated"
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
     *     description="Update an Movement Type",
     *     @Model(type=MovementType::class, groups={"movement_type"})
     * )
     * @SWG\Tag(name="movementTypes")
     *
     * @Rest\View(serializerGroups={"errors"})
     *
     * @param Request $request
     * @param MovementType $movementType
     * @return View
     */
    public function updateMovementType(Request $request, MovementType $movementType)
    {
        $form = $this->createForm(MovementTypeType::class, $movementType);
        $form->submit($request->request->all(), false);
        if ($form->isValid()) {
            $this->apiManager->save($movementType);
            return $this->view(null, Response::HTTP_NO_CONTENT);
        }
        throw new FormValidationException($form);
    }

    /**
     * @Rest\Delete("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=204,
     *     description="Movement Type is removed"
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Deleting errors"
     *     )
     * )
     * @SWG\Tag(name="movementTypes")
     *
     * @Rest\View()
     *
     * @param MovementType $movementType
     *
     * @return View
     */
    public function removeMovementType(MovementType $movementType)
    {
        $this->apiManager->delete($movementType);
        return $this->view(null, Response::HTTP_NO_CONTENT);
    }
}
