<?php

namespace App\Controller\API;

use App\Entity\Department;
use App\Exception\FormValidationException;
use App\Form\DepartmentType;
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
 * Class DepartmentController
 * @package App\Controller\API
 * @Route("/departments")
 */
class DepartmentController extends AbstractFOSRestController
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
     *     description="Returns Department by id",
     *     @SWG\Schema(
     *         ref=@Model(type=Department::class, groups={"department", "id"})
     *     )
     * )
     * @SWG\Tag(name="departments")
     * @Rest\View(serializerGroups={"department", "id"})
     *
     * @param Department $department
     *
     * @return View
     */
    public function showDepartment(Department $department)
    {
        return $this->view($department, Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of departments",
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
     *     name="name",
     *     in="query",
     *     type="string",
     *     description="The field used to filter by name"
     * )
     * @SWG\Tag(name="departments")
     *
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="page number.")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="0", description="page size.")
     * @Rest\QueryParam(name="sort_by", nullable=true, default="id", description="order by")
     * @Rest\QueryParam(name="region_name", map=true, nullable=false, description="filter by region name. example: region_name[eq]=value")
     * @Rest\QueryParam(name="search", map=false, nullable=true, description="search. example: search=text")
     * @Rest\QueryParam(
     *     name="sort", requirements="(asc|desc)",
     *      nullable=true, default="asc",
     *      description="sorting order asc|desc"
     * )
     * @Rest\QueryParam(name="name", map=true, nullable=false, description="filter by name. example: name[eq]=value")
     * @Rest\QueryParam(name="region", nullable=false, description="filter by region id. example: region[eq]=value")
     * @Rest\QueryParam(name="startDate",
     *      map=true, nullable=false,
     *      description="filter by start date. example: startDate[eq]=value"
     * )
     * @Rest\QueryParam(name="disappearanceDate",
     *      map=true, nullable=false,
     *      description="filter by disappearance date. example: disappearanceDate[eq]=value"
     * )
     * @Rest\QueryParam(name="search", map=false, nullable=true, description="search. example: search=text")
     *
     * @Rest\View()
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return View
     */
    public function listDepartment(ParamFetcherInterface $paramFetcher)
    {
        $records = $this->apiManager->findRecordsByEntityName(Department::class, $paramFetcher);
        return $this->view($records, Response::HTTP_OK);
    }
    /**
     *
     * @Rest\Get("/findByCriteria")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of an departments filtred ",
     *     @SWG\Schema(
     *         @SWG\Items(ref=@Model(type=ApiResponse::class))
     *     )
     * )
     *
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
     *
     * @SWG\Parameter(
     *     name="region",
     *      in="query",
     *      type="string",
     *     description="regions id"
     * )
     *
     * @SWG\Tag(name="departments")
     *
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="page number.")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="0", description="page size.")
     * @Rest\QueryParam(name="region", nullable=true, default="", description="region id")
     *
     * @Rest\View()
     *
     * @param ParamFetcherInterface $paramFetcher
     * @param ApiManager $apiManager
     * @return View
     */
    public function listCommuneByCriteria(ParamFetcherInterface $paramFetcher,ApiManager $apiManager){

        $records =$apiManager->findRecordsByEntityNameAndCriteria(Department::class,$paramFetcher);
        return $this->view($records, Response::HTTP_OK);
    }
    /**
     * @Rest\Post("/")
     *
     * @SWG\Response(
     *     response=201,
     *     description="Returns created Department",
     *     @SWG\Schema(
     *         ref=@Model(type=Department::class, groups={"department", "id"})
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Creation error"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Add Department",
     *     @Model(type=Department::class, groups={"department"})
     * )
     * @SWG\Tag(name="departments")
     *
     * @Rest\View(serializerGroups={"department", "id"})
     *
     * @param Request $request
     * @return View
     */
    public function postDepartment(Request $request)
    {
        $form = $this->createForm(DepartmentType::class);
        $form->submit($request->request->all());
        if ($form->isValid()) {
            $department = $this->apiManager->save($form->getData());
            return $this->view($department, Response::HTTP_CREATED);
        }
        throw new FormValidationException($form);
    }

    /**
     * @Rest\Put("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=204,
     *     description="Department is updated"
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Update error"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Department not found"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Update a Department",
     *     @Model(type=Department::class, groups={"department"})
     * )
     * @SWG\Tag(name="departments")
     *
     * @Rest\View()
     *
     * @param Request $request
     * @param Department $department
     * @return View
     */
    public function updateDepartment(Request $request, Department $department)
    {
        $form = $this->createForm(DepartmentType::class, $department);
        $form->submit($request->request->all(), false);
        if ($form->isValid()) {
            $this->apiManager->save($department);
            return $this->view(null, Response::HTTP_NO_CONTENT);
        }
        throw new FormValidationException($form);
    }

    /**
     * @Rest\Delete("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=204,
     *     description="Department is removed"
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Deleting errors"
     *     )
     * )
     * @SWG\Tag(name="departments")
     *
     * @Rest\View()
     *
     * @param Department $department
     *
     * @return View
     */
    public function removeDepartment(Department $department)
    {
        $this->apiManager->delete($department);
        return $this->view(null, Response::HTTP_NO_CONTENT);
    }
}
