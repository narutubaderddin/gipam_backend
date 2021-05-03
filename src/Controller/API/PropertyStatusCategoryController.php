<?php

namespace App\Controller\API;

use App\Entity\PropertyStatusCategory;
use App\Exception\FormValidationException;
use App\Form\PropertyStatusCategoryType;
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

/**
 * Class PropertyStatusCategoryController
 * @package App\Controller\API
 * @Route("/propertyStatusCategories")
 */
class PropertyStatusCategoryController extends AbstractFOSRestController
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
     *     description="Returns PropertyStatusCategory by id",
     *     @SWG\Schema(
     *         ref=@Model(type=PropertyStatusCategory::class, groups={"category"})
     *     )
     * )
     * @SWG\Tag(name="propertyStatusCategories")
     * @Rest\View(serializerGroups={"category"})
     *
     * @param PropertyStatusCategory $category
     *
     * @return Response
     */
    public function showPropertyStatusCategory(PropertyStatusCategory $category)
    {
        return $this->view($category, Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of an PropertyStatusCategory",
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
     * @SWG\Tag(name="propertyStatusCategories")
     *
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="page number.")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="0", description="page size.")
     * @Rest\QueryParam(name="sort_by", nullable=true, default="id", description="order by")
     * @Rest\QueryParam(name="sort", requirements="(asc|desc)", nullable=true, default="asc", description="tri order asc|desc")
     * @Rest\QueryParam(name="label", map=true, nullable=false, description="filter by label. example: label[eq]=value")
     * @Rest\QueryParam(name="active", map=true, nullable=false, description="filter by active. example: active[eq]=1")
     * @Rest\QueryParam(name="search", map=false, nullable=true, description="search. example: search=text")
     * @Rest\View()
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return Response
     */
    public function listPropertyStatusCategories(ParamFetcherInterface $paramFetcher)
    {
        $records = $this->apiManager->findRecordsByEntityName(PropertyStatusCategory::class, $paramFetcher);
        return $this->view($records, Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/")
     *
     * @SWG\Response(
     *     response=201,
     *     description="Returns created PropertyStatusCategory",
     *     @SWG\Schema(
     *         ref=@Model(type=PropertyStatusCategory::class, groups={"category"})
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Creation error"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Add PropertyStatusCategory",
     *     @Model(type=PropertyStatusCategory::class, groups={"category"})
     * )
     * @SWG\Tag(name="propertyStatusCategories")
     *
     * @Rest\View(serializerGroups={"category"})
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function postPropertyStatusCategory(Request $request)
    {
        $form = $this->createForm(PropertyStatusCategoryType::class);
        $form->submit($request->request->all());
        if ($form->isValid()) {
            $category = $this->apiManager->save($form->getData());
            return $this->view($category, Response::HTTP_CREATED);
        } else {
            throw new FormValidationException($form);
        }
    }

    /**
     * @Rest\Put("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=204,
     *     description="PropertyStatusCategory is updated"
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Update error"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Update a PropertyStatusCategory",
     *     @Model(type=PropertyStatusCategory::class, groups={"category"})
     * )
     * @SWG\Tag(name="propertyStatusCategories")
     *
     * @Rest\View()
     *
     * @param Request $request
     * @param PropertyStatusCategory $category
     *
     * @return Response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updatePropertyStatusCategory(Request $request, PropertyStatusCategory $category)
    {
        $form = $this->createForm(PropertyStatusCategoryType::class, $category);
        $form->submit($request->request->all(), false);

        if ($form->isValid()) {
            $this->apiManager->save($category);
            return $this->view(null, Response::HTTP_NO_CONTENT);
        } else {
            throw new FormValidationException($form);
        }
    }

    /**
     * @Rest\Delete("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=204,
     *     description="PropertyStatusCategory is removed"
     *     )
     * )
     * @SWG\Tag(name="propertyStatusCategories")
     *
     * @Rest\View()
     *
     * @param PropertyStatusCategory $category
     *
     * @return Response
     */
    public function removePropertyStatusCategory(PropertyStatusCategory $category)
    {
        $this->apiManager->delete($category);
        return $this->view(null, Response::HTTP_NO_CONTENT);
    }
}