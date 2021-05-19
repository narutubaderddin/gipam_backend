<?php

namespace App\Controller\API;

use App\Entity\Correspondent;
use App\Exception\FormValidationException;
use App\Form\CorrespondentType;
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
 * Class CorrespondentController
 * @package App\Controller\API
 * @Route("/correspondents")
 */
class CorrespondentController extends AbstractFOSRestController
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
    ) {
        $this->apiManager = $apiManager;
        $this->validator = $validator;
    }
    /**
     * @Rest\Get("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns Correspondent by id",
     *     @SWG\Schema(
     *         ref=@Model(type=Correspondent::class, groups={"correspondent", "id"})
     *     )
     * )
     * @SWG\Tag(name="correspondents")
     * @Rest\View(serializerGroups={"correspondent", "id"})
     *
     * @param Correspondent $correspondent
     *
     * @return View
     */
    public function showCorrespondent(Correspondent $correspondent)
    {
        return $this->view($correspondent, Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of correspondents",
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
     * @SWG\Tag(name="correspondents")
     *
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="page number.")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="0", description="page size.")
     * @Rest\QueryParam(name="sort_by", nullable=true, default="id", description="order by")
     * @Rest\QueryParam(
     *     name="sort", requirements="(asc|desc)",
     *      nullable=true, default="asc",
     *      description="sorting order asc|desc"
     * )
     * @Rest\QueryParam(name="firstName", map=true, nullable=false, description="filter by firstName. example: firstName[eq]=value")
     * @Rest\QueryParam(name="lastName", map=true, nullable=false, description="filter by lastName. example: lastName[eq]=value")
     * @Rest\QueryParam(name="function", map=true, nullable=false, description="filter by function. example: function[eq]=value")
     * @Rest\QueryParam(name="phone", map=true, nullable=true, description="filter by phone. example: phone[eq]=value")
     * @Rest\QueryParam(name="fax", map=true, nullable=true, description="filter by fax. example: fax[eq]=value")
     * @Rest\QueryParam(name="mail", map=true, nullable=false, description="filter by mail. example: mail[eq]=value")
     * @Rest\QueryParam(name="login", map=true, nullable=false, description="filter by login. example: login[eq]=value")
     * @Rest\QueryParam(name="establishment", nullable=true, description="filter by establishment id. example: establishment[eq]=value")
     * @Rest\QueryParam(name="subDivisions_id", nullable=true, description="filter by subDivision id. example: subDivisions_id[eq]=value")
     * @Rest\QueryParam(name="services_id", nullable=true, description="filter by service id. example: services_id[eq]=value")
     * @Rest\QueryParam(name="establishment_label", map=true, nullable=false, description="filter by establishment label. example: establishment_label[eq]=value")
     * @Rest\QueryParam(name="subDivisions_label", map=true, nullable=false, description="filter by subDivision label. example: subDivisions_label[eq]=value")
     * @Rest\QueryParam(name="services_label", map=true, nullable=false, description="filter by service label. example: services_label[eq]=value")
     *
     * @Rest\QueryParam(name="startDate",
     *      map=true, nullable=false,
     *      description="filter by start date. example: startDate[eq]=value"
     * )
     * @Rest\QueryParam(name="endDate",
     *      map=true, nullable=false,
     *      description="filter by end date. example: endDate[eq]=value"
     * )
     * @Rest\QueryParam(name="search", map=false, nullable=true, description="search. example: search=text")
     *
     * @Rest\View()
     *
     * @param ParamFetcherInterface $paramFetcher
     * @param Request $request
     * @return View
     */
    public function listCorrespondent(ParamFetcherInterface $paramFetcher, Request $request)
    {
        $serializerGroups = $request->get('serializer_group') ?? null;
        if ($serializerGroups) {
            $serializerGroups = json_decode($serializerGroups, true);
            $context = new Context();
            $context->setGroups($serializerGroups);
            $records = $this->apiManager->findRecordsByEntityName(Correspondent::class, $paramFetcher);
            return $this->view($records, Response::HTTP_OK)->setContext($context);
        }
        $records = $this->apiManager->findRecordsByEntityName(Correspondent::class, $paramFetcher);
        return $this->view($records, Response::HTTP_OK);
    }
    /**
     * @Rest\Post("/")
     *
     * @SWG\Response(
     *     response=201,
     *     description="Returns created correspondent",
     *     @SWG\Schema(
     *         ref=@Model(type=Correspondent::class, groups={"correspondent", "id"})
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Creation error"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Add Correspondent",
     *     @Model(type=Correspondent::class, groups={"correspondent"})
     * )
     * @SWG\Tag(name="correspondents")
     *
     * @Rest\View(serializerGroups={"correspondent", "id"})
     *
     * @param Request $request
     * @return View
     */
    public function postCorrespondent(Request $request)
    {
        $form = $this->createForm(CorrespondentType::class);
        $form->submit($request->request->all());
        if ($form->isValid()) {
            $correspondent = $this->apiManager->save($form->getData());
            return $this->view($correspondent, Response::HTTP_CREATED);
        }
        throw new FormValidationException($form);
    }
    /**
     * @Rest\Put("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=204,
     *     description="Correspondent is updated"
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Update error"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Correspondent not found"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Update a Correspondent",
     *     @Model(type=Correspondent::class, groups={"correspondent"})
     * )
     * @SWG\Tag(name="correspondents")
     *
     * @Rest\View()
     *
     * @param Request $request
     * @param Correspondent $correspondent
     * @return View
     */
    public function updateBuilding(Request $request, Correspondent $correspondent)
    {
        $form = $this->createForm(CorrespondentType::class, $correspondent);
        $form->submit($request->request->all(), false);
        if ($form->isValid()) {
            $this->apiManager->save($correspondent);
            return $this->view(null, Response::HTTP_NO_CONTENT);
        }
        throw new FormValidationException($form);
    }
    /**
     * @Rest\Delete("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=204,
     *     description="Correspondent is removed"
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Deleting errors"
     *     )
     * )
     * @SWG\Tag(name="correspondents")
     *
     * @Rest\View()
     *
     * @param Correspondent $correspondent
     *
     * @return View
     */
    public function removeCorrespondent(Correspondent $correspondent)
    {
        $this->apiManager->delete($correspondent);
        return $this->view(null, Response::HTTP_NO_CONTENT);
    }
}
