<?php


namespace App\Controller\API;


use App\Entity\ArtWork;
use App\Model\ApiResponse;
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
     * @Rest\View(serializerGroups={"art_work_list", "id", "art_work_details"})
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
     * @Rest\Get("/search")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of an ArtWork",
     *     @SWG\Schema(
     *         @SWG\Items(ref=@Model(type=ApiResponse::class))
     *     )
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
     * @SWG\Tag(name="art_works")
     *
     * @Rest\QueryParam(name="sort_by", nullable=true, default="id", description="order by")
     * @Rest\QueryParam(name="sort", requirements="(asc|desc)", nullable=true, default="asc", description="tri order asc|desc")
     * @Rest\QueryParam(name="title", nullable=true, description="filter by title. example: title[eq]=text")
     * @Rest\QueryParam(name="width", nullable=true, description="filter by width. example: width[eq]=text")
     * @Rest\QueryParam(name="height", nullable=true, description="filter by height. example: height[eq]=text")
     * @Rest\QueryParam(name="depth", nullable=true, description="filter by depth. example: depth[eq]=text")
     * @Rest\QueryParam(name="diameter", nullable=true, description="filter by diameter. example: diameter[eq]=text")
     * @Rest\QueryParam(name="weight", nullable=true, description="filter by weight. example: weight[eq]=text")
     * @Rest\QueryParam(name="stopNumber", nullable=true, description="filter by stopNumber. example: weight[eq]=text")
     * @Rest\QueryParam(name="numberOfUnit", nullable=true, description="filter by number Of Unit. weight[eq]=1")
     * @Rest\QueryParam(name="description", nullable=false, description="filter by description. example: description[eq]=text")
     * @Rest\QueryParam(name="creationDate", map=true, nullable=false, description="filter by creationDate. example: creationDate[lt]=value")
     * @Rest\QueryParam(name="depositDate", map=true, nullable=false, description="filter by depositDate. example: depositDate[lt]=value")
     * @Rest\QueryParam(name="insuranceValueDate", map=true, nullable=false, description="filter by insuranceValueDate. example: insuranceValueDate[lt]=value")
     * @Rest\QueryParam(name="totalLength", nullable=false, description="filter by totalLength. example: totalLength[eq]=1")
     * @Rest\QueryParam(name="totalLength", nullable=false, description="filter by totalLength. example: totalLength[eq]=1")
     * @Rest\QueryParam(name="totalLength", nullable=false, description="filter by totalLength. example: totalLength[eq]=1")
     * @Rest\QueryParam(name="totalWidth", nullable=false, description="filter by totalWidth. example: totalWidth[eq]=1")
     * @Rest\QueryParam(name="totalHeight", nullable=false, description="filter by totalHeight. example: totalHeight[eq]=1")
     * @Rest\QueryParam(name="registrationSignature", nullable=false, description="filter by registrationSignature. example: registrationSignature[eq]=text")
     * @Rest\QueryParam(name="descriptiveWords", nullable=false, description="filter by descriptiveWords. example: descriptiveWords[eq]=text")
     * @Rest\QueryParam(name="insuranceValue", nullable=false, description="filter by insuranceValue. example: insuranceValue[eq]=1")
     * @Rest\QueryParam(name="denomination", map=true, nullable=true, description="filter by denomination. example: denomination[eq]=value")
     * @Rest\QueryParam(name="field", map=true, nullable=true, description="filter by field. example: field[eq]=value")
     * @Rest\QueryParam(name="search", nullable=false, description="filter by search. example: search[eq]=1")
     * @Rest\QueryParam(name="mode", nullable=false, description="filter by mode. example: mode[eq]=1")
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="page number.")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="20", description="page size.")
     * @Rest\QueryParam(name="sort_by", nullable=true, default="id", description="order by")
     * @Rest\QueryParam(name="sort", requirements="(asc|desc)", nullable=true, default="asc", description="tri order asc|desc")
     * @Rest\View(serializerGroups={"response","art_work_list"})
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return Response
     */
    public function searchArtWorks(ParamFetcherInterface $paramFetcher)
    {
        $records = $this->apiManager->searchByEntityName(ArtWork::class, $paramFetcher);
        return $this->view($records, Response::HTTP_OK);
    }
}