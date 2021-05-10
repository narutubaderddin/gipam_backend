<?php


namespace App\Controller\API;


use App\Entity\Depositor;
use App\Exception\FormValidationException;
use App\Form\DepositorType;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use App\Services\ApiManager;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View;
use Swagger\Annotations as SWG;
use App\Model\ApiResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\Model;

/**
 * Class DepositorController
 * @package App\Controller\API
 * @Rest\Route("/depositors")
 */
class DepositorController extends AbstractFOSRestController
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
     *     description="Returns a Depositor by id",
     *     @SWG\Schema(
     *         ref=@Model(type=Depositor::class, groups={"depositor", "id"})
     *     )
     * )
     * @SWG\Tag(name="depositors")
     * @Rest\View(serializerGroups={"depositor", "id"})
     *
     * @param Depositor $depositor
     *
     * @return View
     */
    public function showDepositor(Depositor $depositor)
    {
        return $this->view($depositor, Response::HTTP_OK);
    }

    /**
     *
     * @Rest\Get("/")
     *
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of depositor",
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
     * @SWG\Tag(name="depositors")
     *
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="page number.")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="0", description="page size.")
     * @Rest\QueryParam(name="sort_by", nullable=true, default="id", description="order by")
     * @Rest\QueryParam(name="sort", requirements="(asc|desc)", nullable=true, default="asc", description="tri order asc|desc")
     * @Rest\QueryParam(name="name",map=true, nullable=false, description="filter by name. example: name[eq]=value")
     * @Rest\QueryParam(name="acronym",map=true, nullable=false, description="filter by acronym. example: acronym[eq]=value")
     * @Rest\QueryParam(name="address",map=true, nullable=false, description="filter by address. example: address[eq]=value")
     * @Rest\QueryParam(name="city",map=true, nullable=false, description="filter by city. example: city[eq]=value")
     * @Rest\QueryParam(name="department",map=true, nullable=false, description="filter by department. example: department[eq]=value")
     * @Rest\QueryParam(name="distrib",map=true, nullable=false, description="filter by distrib. example: distrib[eq]=value")
     * @Rest\QueryParam(name="fax",map=true, nullable=false, description="filter by fax. example: fax[eq]=value")
     * @Rest\QueryParam(name="mail",map=true, nullable=false, description="filter by mail. example: mail[eq]=value")
     * @Rest\QueryParam(name="startDate",map=true, nullable=false, description="filter by startDate. example: startDate[eq]=value")
     * @Rest\QueryParam(name="endDate",map=true, nullable=false, description="filter by endDate. example: endDate[eq]=value")
     * @Rest\QueryParam(name="comment",map=true, nullable=false, description="filter by comment. example: comment[eq]=value")
     * @Rest\QueryParam(name="contact",map=true, nullable=false, description="filter by contact. example: contact[eq]=value")
     * @Rest\QueryParam(name="phone",map=true, nullable=false, description="filter by phone. example: phone[eq]=value")
     * @Rest\QueryParam(name="search", map=false, nullable=true, description="search. example: search=text")
     * @Rest\View(serializerGroups={"depositors", "id", "response"})
     *
     * @param ParamFetcherInterface $paramFetcher
     * @return View
     *
     */
    public function listDepositor(ParamFetcherInterface $paramFetcher)
    {
        $records = $this->apiManager->findRecordsByEntityName(Depositor::class,$paramFetcher);
        return $this->view($records, Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/")
     *
     * @SWG\Response(
     *     response=201,
     *     description="Returns created Attachment Type",
     *     @SWG\Schema(
     *         ref=@Model(type=Depositor::class, groups={"depositor"})
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Creation error"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Add Depositor",
     *     @Model(type=Depositor::class, groups={"depositor"})
     * )
     * @SWG\Tag(name="depositors")
     *
     * @Rest\View(serializerGroups={"depositor", "id"})
     *
     * @param Request $request
     * @return View
     */
    public function postDepositor(Request $request)
    {
        $form = $this->createForm(DepositorType::class);
        $form->submit($request->request->all());
        if ($form->isValid()) {
            $depositor = $this->apiManager->save($form->getData());
            return $this->view($depositor, Response::HTTP_CREATED);
        }
        throw new FormValidationException($form);
    }

    /**
     * @Rest\Put("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=204,
     *     description="Attachment Type is updated"
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Update error"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Attachment Type not found"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Update an Attachment Type",
     *     @Model(type=Depositor::class, groups={"depositor"})
     * )
     * @SWG\Tag(name="depositors")
     *
     * @Rest\View()
     *
     * @param Request $request
     * @param Depositor $depositor
     * @return View
     */
    public function updateDepositor(Request $request, Depositor $depositor)
    {
        $form = $this->createForm(DepositorType::class, $depositor);
        $form->submit($request->request->all(), false);
        if ($form->isValid()) {
            $this->apiManager->save($depositor);
            return $this->view(null, Response::HTTP_NO_CONTENT);
        }
        throw new FormValidationException($form);
    }

    /**
     * @Rest\Delete("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=204,
     *     description="Attachment Type is removed"
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Deleting errors"
     *     )
     * )
     * @SWG\Tag(name="depositors")
     *
     * @Rest\View()
     *
     * @param Depositor $depositor
     *
     * @return View
     */
    public function removeDepositor(Depositor $depositor)
    {
        $this->apiManager->delete($depositor);
        return $this->view(null, Response::HTTP_NO_CONTENT);
    }

}