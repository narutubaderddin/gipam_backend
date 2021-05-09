<?php


namespace App\Controller\API;


use App\Entity\AuthorType;
use App\Form\AuthorTypesType;
use App\Services\ApiManager;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Swagger\Annotations as SWG;
use App\Model\ApiResponse;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\Model;
use App\Exception\FormValidationException;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AuthorTypeController
 * @package App\Controller\API
 * @Rest\Route("/authorTypes")
 */
class AuthorTypeController extends  AbstractFOSRestController
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
     *     description="Returns AuthorType by id",
     *     @SWG\Schema(
     *         ref=@Model(type=AuthorType::class, groups={"authorType", "id"})
     *     )
     * )
     * @SWG\Tag(name="authorTypes")
     * @Rest\View(serializerGroups={"authorType", "id"})
     *
     * @param AuthorType $authorType
     *
     * @return View
     */
    public function showAuthorType(AuthorType $authorType)
    {
        return $this->view($authorType, Response::HTTP_OK);
    }
    /**
     * @Rest\Get("/")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of AuthorTypes",
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
     * * @SWG\Parameter(
     *     name="label",
     *     in="query",
     *     type="string",
     *     description="The field used to filter by label"
     * )
     * @SWG\Tag(name="authorTypes")
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
     * @Rest\QueryParam(name="active", map=true, nullable=false, description="filter by active. example: active[eq]=1")
     * @Rest\QueryParam(name="author", map=true, nullable=false, description="filter by author. example: author[eq]=1")
     * @Rest\QueryParam(name="search", map=false, nullable=true, description="search. example: search=text")
     *
     *
     * @Rest\View()
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return View
     */
    public function listAuthorType(ParamFetcherInterface $paramFetcher)
    {
        $records = $this->apiManager->findRecordsByEntityName(AuthorType::class, $paramFetcher);
        return $this->view($records, Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/")
     *
     * @SWG\Response(
     *     response=201,
     *     description="Returns created Author Type",
     *     @SWG\Schema(
     *         ref=@Model(type=AuthorType::class, groups={"authorType", "id"})
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Creation error"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Add Author Type",
     *     @Model(type=AuthorType::class, groups={"authorType"})
     * )
     * @SWG\Tag(name="authorTypes")
     *
     * @Rest\View(serializerGroups={"authorType", "id"})
     *
     * @param Request $request
     * @return View
     */
    public function postAuthorTypes(Request $request)
    {
        $form = $this->createForm(AuthorTypesType::class);
        $form->submit($request->request->all());
        if ($form->isValid()) {
            $authorType = $this->apiManager->save($form->getData());
            return $this->view($authorType, Response::HTTP_CREATED);
        }
        throw new FormValidationException($form);
    }
    /**
     * @Rest\Put("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=204,
     *     description="Author Type is updated"
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Update error"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Author Type not found"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Update an Author Type",
     *     @Model(type=AuthorType::class, groups={"authorType"})
     * )
     * @SWG\Tag(name="authorTypes")
     *
     * @Rest\View()
     *
     * @param Request $request
     * @param AuthorType $authorType
     * @return View
     */
    public function updateAuthorType(Request $request, AuthorType $authorType)
    {
        $form = $this->createForm(AuthorTypesType::class, $authorType);
        $form->submit($request->request->all(), false);
        if ($form->isValid()) {
            $this->apiManager->save($authorType);
            return $this->view(null, Response::HTTP_NO_CONTENT);
        }
        throw new FormValidationException($form);
    }
    /**
     * @Rest\Delete("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=204,
     *     description="Author Type Type is removed"
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Deleting errors"
     *     )
     * )
     * @SWG\Tag(name="authorTypes")
     *
     * @Rest\View()
     *
     * @param AuthorType $authorType
     *
     * @return View
     */
    public function removeAuthorType(AuthorType $authorType)
    {
        $this->apiManager->delete($authorType);
        return $this->view(null, Response::HTTP_NO_CONTENT);
    }


}