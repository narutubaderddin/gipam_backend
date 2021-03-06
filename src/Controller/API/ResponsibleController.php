<?php

namespace App\Controller\API;


use App\Entity\Responsible;
use App\Exception\FormValidationException;
use App\Form\ResponsibleType;
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
 * Class ResponsibleController
 * @package App\Controller\API
 * @Route("/responsibles")
 */
class ResponsibleController extends AbstractFOSRestController
{
    /**
     * @var ApiManager
     */
    protected $apiManager;


    public function __construct(
        ApiManager $apiManager
    ) {
        $this->apiManager = $apiManager;

    }

    /**
     * @Rest\Get("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns Responsible by id",
     *     @SWG\Schema(
     *         ref=@Model(type=Responsible::class, groups={"responsible", "id"})
     *     )
     * )
     * @SWG\Tag(name="responsibles")
     * @Rest\View(serializerGroups={"responsible", "id"})
     *
     * @param Responsible $responsible
     *
     * @return View
     */
    public function showResponsible(Responsible $responsible)
    {
        return $this->view($responsible, Response::HTTP_OK);
    }
    /**
     * @Rest\Get("/")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of responsibles",
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
     * @SWG\Tag(name="responsibles")
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
     * @Rest\QueryParam(name="function", map=true, nullable=true, description="filter by function. example: function[eq]=value")
     * @Rest\QueryParam(name="phone", map=true, nullable=true, description="filter by phone. example: phone[eq]=value")
     * @Rest\QueryParam(name="fax", map=true, nullable=true, description="filter by fax. example: fax[eq]=value")
     * @Rest\QueryParam(name="mail", map=true, nullable=true, description="filter by mail. example: mail[eq]=value")
     * @Rest\QueryParam(name="login", map=true, nullable=true, description="filter by login. example: login[eq]=value")
     * @Rest\QueryParam(name="region", nullable=true, description="filter by region id. example: region[eq]=value")
     * @Rest\QueryParam(name="buildings_id", nullable=true, description="filter by buildings id. example: buildings_id[eq]=value")
     * @Rest\QueryParam(name="departments_id", nullable=true, description="filter by batiment id. example: department_id[eq]=value")
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
     *
     * @return View
     */
    public function listResponsible(ParamFetcherInterface $paramFetcher, Request $request)
    {
        $serializerGroups = $request->get('serializer_group', '["responsible", "id", "short"]');
        $serializerGroups = json_decode($serializerGroups, true);
        $serializerGroups[] = "response";
        $context = new Context();
        $context->setGroups($serializerGroups);
        $records = $this->apiManager->findRecordsByEntityName(Responsible::class, $paramFetcher);
        return $this->view($records, Response::HTTP_OK)->setContext($context);
    }
    /**
     * @Rest\Post("/")
     *
     * @SWG\Response(
     *     response=201,
     *     description="Returns created responsible",
     *     @SWG\Schema(
     *         ref=@Model(type=Responsible::class, groups={"responsible", "id"})
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Creation error"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Add Responsible",
     *     @Model(type=Responsible::class, groups={"responsible"})
     * )
     * @SWG\Tag(name="responsibles")
     *
     * @Rest\View(serializerGroups={"responsible", "id"})
     *
     * @param Request $request
     * @return View
     */
    public function postResponsible(Request $request)
    {
        $form = $this->createForm(ResponsibleType::class);
        $form->submit($request->request->all());
        if ($form->isValid()) {
            $responsible = $this->apiManager->save($form->getData());
            return $this->view($responsible, Response::HTTP_CREATED);
        }
        throw new FormValidationException($form);
    }
    /**
     * @Rest\Put("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=204,
     *     description="Responsible is updated"
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Update error"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Responsible not found"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Update a Responsible",
     *     @Model(type=Responsible::class, groups={"responsible"})
     * )
     * @SWG\Tag(name="responsibles")
     *
     * @Rest\View()
     *
     * @param Request $request
     * @param Responsible $responsible
     * @return View
     */
    public function updateBuilding(Request $request, Responsible $responsible)
    {
        $form = $this->createForm(ResponsibleType::class, $responsible);
        $form->submit($request->request->all(), false);
        if ($form->isValid()) {
            $this->apiManager->save($responsible);
            return $this->view(null, Response::HTTP_NO_CONTENT);
        }
        throw new FormValidationException($form);
    }
    /**
     * @Rest\Delete("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=204,
     *     description="Responsible is removed"
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Deleting errors"
     *     )
     * )
     * @SWG\Tag(name="responsibles")
     *
     * @Rest\View()
     *
     * @param Responsible $responsible
     *
     * @return View
     */
    public function removeResponsible(Responsible $responsible)
    {
        $this->apiManager->delete($responsible);
        return $this->view(null, Response::HTTP_NO_CONTENT);
    }
}
