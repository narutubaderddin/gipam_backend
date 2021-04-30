<?php


namespace App\Controller\API;


use App\Entity\Author;
use App\Services\ApiManager;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Swagger\Annotations as SWG;
use App\Model\ApiResponse;
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
     * @Rest\QueryParam(name="active" ,map=true, nullable=false, description="filter by active. example: active[eq]=1")
     * @Rest\View(serializerGroups={"authors","id","response"})
     *
     * @return Response
     *
     * @param ParamFetcherInterface $paramFetcher
     */
    public function listAuthors(ParamFetcherInterface $paramFetcher)
    {
       $records = $this->apiManager->findRecordsByEntityName(Author::class,$paramFetcher);
       return $this->view($records, Response::HTTP_OK);
    }

}