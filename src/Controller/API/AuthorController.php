<?php


namespace App\Controller\API;


use App\Entity\Author;
use App\Exception\FormValidationException;
use App\Form\AuthorType;
use App\Services\ApiManager;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View;
use Swagger\Annotations as SWG;
use App\Model\ApiResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\Model;

/**
 * Class AuthorController
 * @package App\Controller\API
 * @Rest\Route("/authors")
 */
class AuthorController extends  AbstractFOSRestController
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
     *     description="Returns an Author by id",
     *     @SWG\Schema(
     *         ref=@Model(type=Author::class, groups={"authors"})
     *     )
     * )
     * @SWG\Tag(name="authors")
     * @Rest\View(serializerGroups={"authors", "short"})
     *
     * @param Author $author
     *
     * @return View
     */
    public function showAuthor(Author $author)
    {
        return $this->view($author, Response::HTTP_OK);
    }

    /**
     *
     * @Rest\Get("/")
     *
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of authors",
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
     * @SWG\Tag(name="authors")
     *
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="page number.")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="0", description="page size.")
     * @Rest\QueryParam(name="sort_by", nullable=true, default="id", description="order by")
     * @Rest\QueryParam(name="sort", requirements="(asc|desc)", nullable=true, default="asc", description="tri order asc|desc")
     * @Rest\QueryParam(name="firstName" ,map=true, nullable=false, description="filter by firstName. example: firstName[eq]=1")
     * @Rest\QueryParam(name="lastName" ,map=true, nullable=false, description="filter by lastName. example: lastName[eq]=1")
     * @Rest\QueryParam(name="active" ,map=true, nullable=false, description="filter by active. example: active[eq]=1")
     * @Rest\QueryParam(name="type" , nullable=false, description="filter by type id. example: type[eq]=1")
     * @Rest\QueryParam(name="search", map=false, nullable=true, description="search. example: search=text")
     * @Rest\View(serializerGroups={"id", "response", "short"})
     *
     * @param ParamFetcherInterface $paramFetcher
     *@return View
     *
     */
    public function listAuthors(ParamFetcherInterface $paramFetcher)
    {
       $records = $this->apiManager->findRecordsByEntityName(Author::class,$paramFetcher);
       return $this->view($records, Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/")
     *
     * @SWG\Response(
     *     response=201,
     *     description="Returns created Author",
     *     @SWG\Schema(
     *         ref=@Model(type=Author::class, groups={"authors"})
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Creation error"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Add Author",
     *     @Model(type=Author::class, groups={"authors"})
     * )
     * @SWG\Tag(name="authors")
     *
     * @Rest\View(serializerGroups={"authors", "short"})
     *
     * @param Request $request
     *
     * @return View
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function postAuthor(Request $request)
    {
        $form = $this->createForm(AuthorType::class);
        $form->submit($request->request->all());
        if ($form->isValid()) {
            $author = $this->apiManager->save($form->getData());
            return $this->view($author, Response::HTTP_CREATED);
        } else {
            throw new FormValidationException($form);
        }
    }

    /**
     * @Rest\Put("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=204,
     *     description="Author is updated"
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Update error"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Update an Author",
     *     @Model(type=Author::class, groups={"authors"})
     * )
     * @SWG\Tag(name="authors")
     *
     * @Rest\View()
     *
     * @param Request $request
     * @param Author $author
     *
     * @return View
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateAuthor(Request $request, Author $author)
    {
        $form = $this->createForm(AuthorType::class, $author);
        $form->submit($request->request->all(), false);

        if ($form->isValid()) {
            $this->apiManager->save($author);
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
     *     description="Author is removed"
     *     )
     * )
     * @SWG\Tag(name="authors")
     *
     * @Rest\View()
     *
     * @param Author $author
     *
     * @return View
     */
    public function removeAuthor(Author $author)
    {
        $this->apiManager->delete($author);
        return $this->view(null, Response::HTTP_NO_CONTENT);
    }



}
