<?php


namespace App\Controller\API;


use App\Entity\ArtWork;
use App\Model\ApiResponse;
use App\Repository\ArtWorkRepository;
use App\Repository\FurnitureRepository;
use App\Services\ApiManager;
use App\Services\ArtWorkService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
/**
 * Class ArtWorkController
 * @package App\Controller\API
 * @Rest\Route("/artWorks")
 */
class ArtWorkController extends AbstractFOSRestController
{
    /**
     * @var ApiManager
     */
    protected $apiManager;
    /**
     * @var ValidatorInterface
     */
    protected $validator;
    /**
     * @var ArtWorkService
     */
    protected $artWorkService;

    /**
     * ArtWorkController constructor.
     * @param ApiManager $apiManager
     * @param ValidatorInterface $validator
     * @param ArtWorkService $artWorkService
     */
    public function __construct(
        ApiManager $apiManager,
        ValidatorInterface $validator,
        ArtWorkService $artWorkService
    )
    {
        $this->apiManager = $apiManager;
        $this->validator = $validator;
        $this->artWorkService = $artWorkService;
    }

    /**
     * @Rest\Get("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns Art work by id",
     *     @SWG\Schema(
     *         ref=@Model(type=ArtWork::class, groups={"location_type", "id"})
     *     )
     * )
     * @SWG\Tag(name="ArtWorks")
     * @Rest\View(serializerGroups={"art_work_list", "id"})
     *
     * @param ArtWork $artWork
     * @return View
     */
    public function showArtWork(ArtWork $artWork){
        return $this->view($artWork, Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of Art Works",
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
     * @SWG\Tag(name="ArtWorks")
     *
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="page number.")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="20", description="page size.")
     * @Rest\QueryParam(name="sort_by", nullable=true, default="id", description="order by")
     * @Rest\QueryParam(
     *     name="sort", requirements="(asc|desc)",
     *      nullable=true, default="asc",
     *      description="sorting order asc|desc"
     * )
     * @Rest\View( serializerGroups={"art_work_list","style_furniture","materialTechnique_furniture","status_furniture",
     *     "response","era_furniture","denomination_furniture","field_furniture","mouvement_furniture",
     *     "furniture_author","id"},serializerEnableMaxDepthChecks=true)
     *
     * @param Request $request
     * @param ParamFetcherInterface $paramFetcher
     * @param ArtWorkService $artWorkService
     * @param FurnitureRepository $furnitureRepository
     * @return View
     */
    public function listArtWorks(Request $request,ParamFetcherInterface $paramFetcher, ArtWorkService $artWorkService , FurnitureRepository $furnitureRepository){
         $records=$artWorkService->findArtWorkRecord($paramFetcher,$request->get('filter',[])
             ,$request->get('advancedFilter',[]),$request->get('headerFilters',[]));
        return $this->view($records,Response::HTTP_OK);
    }

    /**
     * @param ParamFetcherInterface $paramFetcher
     * @param ArtWorkService $artWorkService
     * @Rest\Get("/autocompleteData")
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of Art Works",
     *     @SWG\Schema(
     *         @SWG\Items(ref=@Model(type=ApiResponse::class))
     *     )
     * )
     * @Rest\QueryParam(name="query", nullable=true, default="", description="query to search")
     * @Rest\View()
     * @return array
     */
    public function findDescriptionAutocompleteData(ParamFetcherInterface $paramFetcher,ArtWorkService $artWorkService){
        return $artWorkService->findAutocompleteData($paramFetcher->get('query')??"");
    }
}