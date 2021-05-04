<?php

namespace App\Controller\API;

use App\Entity\Field;
use App\Exception\FormValidationException;
use App\Form\FieldType;
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
 * Class FieldController
 * @package App\Controller\API
 * @Route("/fields")
 */
class FieldController extends AbstractFOSRestController
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
     *     description="Returns Field by id",
     *     @SWG\Schema(
     *         ref=@Model(type=Field::class, groups={"field"})
     *     )
     * )
     * @SWG\Tag(name="fields")
     * @Rest\View(serializerGroups={"field"})
     *
     * @param Field $field
     *
     * @return Response
     */
    public function showField(Field $field)
    {
        return $this->view($field, Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of an Field",
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
     * @SWG\Tag(name="fields")
     *
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="page number.")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="0", description="page size.")
     * @Rest\QueryParam(name="sort_by", nullable=true, default="id", description="order by")
     * @Rest\QueryParam(name="sort", requirements="(asc|desc)", nullable=true, default="asc", description="tri order asc|desc")
     * @Rest\QueryParam(name="label", map=true, nullable=false, description="filter by label. example: label[eq]=value")
     * @Rest\QueryParam(name="active" ,map=true, nullable=false, description="filter by active. example: active[eq]=1")
     * @Rest\QueryParam(name="search", map=false, nullable=true, description="search. example: search=text")
     *
     * @Rest\View(serializerGroups={"response","field_list"})
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return Response
     */
    public function listFields(ParamFetcherInterface $paramFetcher)
    {
        $records = $this->apiManager->findRecordsByEntityName(Field::class, $paramFetcher);
        return $this->view($records, Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/")
     *
     * @SWG\Response(
     *     response=201,
     *     description="Returns created Field",
     *     @SWG\Schema(
     *         ref=@Model(type=Field::class, groups={"field"})
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Creation error"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Add Field",
     *     @Model(type=Field::class, groups={"field"})
     * )
     * @SWG\Tag(name="fields")
     *
     * @Rest\View(serializerGroups={"field"})
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function postField(Request $request)
    {
        $form = $this->createForm(FieldType::class);
        $form->submit($request->request->all());
        if ($form->isValid()) {
            $field = $this->apiManager->save($form->getData());
            return $this->view($field, Response::HTTP_CREATED);
        } else {
            throw new FormValidationException($form);
        }
    }

    /**
     * @Rest\Put("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=204,
     *     description="Field is updated"
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Update error"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Update a Field",
     *     @Model(type=Field::class, groups={"field"})
     * )
     * @SWG\Tag(name="fields")
     *
     * @Rest\View()
     *
     * @param Request $request
     * @param Field $field
     *
     * @return Response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateField(Request $request, Field $field)
    {
        $form = $this->createForm(FieldType::class, $field);
        $form->submit($request->request->all(), false);

        if ($form->isValid()) {
            $this->apiManager->save($field);
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
     *     description="Field is removed"
     *     )
     * )
     * @SWG\Tag(name="fields")
     *
     * @Rest\View()
     *
     * @param Field $field
     *
     * @return Response
     */
    public function removeField(Field $field)
    {
        $this->apiManager->delete($field);
        return $this->view(null, Response::HTTP_NO_CONTENT);
    }
}