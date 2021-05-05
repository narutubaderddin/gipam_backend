<?php


namespace App\Controller\API;


use App\Entity\Room;
use App\Repository\RoomRepository;
use App\Services\ApiManager;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use App\Model\ApiResponse;
use Nelmio\ApiDocBundle\Annotation\Model;


/**
 * Class RoomController
 * @package App\Controller\API
 * @Rest\Route("/rooms")
 */

class RoomController extends  AbstractFOSRestController
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
     * @Rest\Get("/")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of rooms",
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
     *     name="label",
     *     in="query",
     *     type="string",
     *     description="The field used to filter by label"
     * )
     * @SWG\Tag(name="rooms")
     *
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="page number.")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="0", description="page size.")
     * @Rest\QueryParam(name="sort_by", nullable=true, default="id", description="order by")
     * @Rest\QueryParam(name="search", map=false, nullable=true, description="search. example: search=text")
     * @Rest\QueryParam(
     *     name="sort", requirements="(asc|desc)",
     *      nullable=true, default="asc",
     *      description="sorting order asc|desc"
     * )
     *
     * @Rest\View()
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return View
     */
    public function listOfRooms(ParamFetcherInterface $paramFetcher)
    {
        $records = $this->apiManager->findRecordsByEntityName(Room::class, $paramFetcher);
        return $this->view($records, Response::HTTP_OK);
    }
    /**
     *
     * @Rest\Get("/findByCriteria")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of rooms filtred ",
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
     *     name="departement",
     *     in="query",
     *     type="string",
     *     description="departements id"
     * )
     * @SWG\Parameter(
     *     name="region",
     *      type="string",
     *     in="query",
     *     description="regions id"
     * )
     * @SWG\Parameter(
     *     name="commune",
     *     type="string",
     *     in="query",
     *     description="commune id"
     * )
     * @SWG\Parameter(
     *     name="batiment",
     *     type="string",
     *     in="query",
     *     description="batiment id"
     * )
     * @SWG\Parameter(
     *     name="site",
     *     type="string",
     *     in="query",
     *     description="site id"
     * )
     *
     * @SWG\Tag(name="rooms")
     *
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="page number.")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="0", description="page size.")
     * @Rest\QueryParam(name="region", nullable=true, default="", description="region id")
     * @Rest\QueryParam(name="departement", nullable=true, default="", description="epartement id")
     * @Rest\QueryParam(name="commune", nullable=true, default="", description="commune id")
     * @Rest\QueryParam(name="batiment", nullable=true, default="", description="batiment id")
     * @Rest\QueryParam(name="site", nullable=true, default="", description="site id")
     *
     * @Rest\View()
     *
     * @param ParamFetcherInterface $paramFetcher
     * @param ApiManager $apiManager
     * @return View
     */
    public function listRoomByCriteria(ParamFetcherInterface $paramFetcher,ApiManager $apiManager){
        $records =$apiManager->findRecordsByEntityNameAndCriteria(Room::class,$paramFetcher);
        return $this->view($records, Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/findRoomsLevelbyCriteria")
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of sites filtred ",
     *     @SWG\Schema(
     *         @SWG\Items(ref=@Model(type=ApiResponse::class))
     *     )
     * )
     * @SWG\Parameter(
     *     name="batiment",
     *     type="string",
     *     in="query",
     *     description="batiment id"
     * )
     * @Rest\QueryParam(name="batiment", nullable=true, default="", description="commune id")
     * @SWG\Tag(name="sites")
     * @param ParamFetcherInterface $paramFetcher
     * @param RoomRepository $roomRepository
     * @return array
     * @Rest\View()
     */
    public function findRoomsLevelByCriteria(ParamFetcherInterface $paramFetcher,RoomRepository $roomRepository){
        $batiment =  $paramFetcher->get('batiment')??"[]";
        return $roomRepository->findRoomsLevelByCriteria($batiment);
    }
}