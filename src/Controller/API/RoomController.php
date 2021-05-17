<?php


namespace App\Controller\API;


use App\Entity\Room;
use App\Exception\FormValidationException;
use App\Form\RoomType;
use App\Repository\RoomRepository;
use App\Services\ApiManager;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
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
     * @Rest\Get("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns Room by id",
     *     @SWG\Schema(
     *         ref=@Model(type=Room::class, groups={"room"})
     *     )
     * )
     * @SWG\Tag(name="rooms")
     * @Rest\View(serializerGroups={"room", "short"})
     *
     * @param Room $room
     *
     * @return View
     */
    public function showRoom(Room $room)
    {
        return $this->view($room, Response::HTTP_OK);
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
     * @Rest\QueryParam(name="level", map=true, nullable=false, description="filter by level. example: level[eq]=value")
     * @Rest\QueryParam(name="reference", map=true, nullable=false, description="filter by reference. example: reference[eq]=value")
     * @Rest\QueryParam(name="startDate", map=true, nullable=false, description="filter by startDate. example: startDate[lt]=value")
     * @Rest\QueryParam(name="endDate", map=true, nullable=false, description="filter by endDate. example: endDate[lt]=value")
     * @Rest\QueryParam(name="building", map=true, nullable=false, description="filter by building id. example: building[eq]=value")
     * @Rest\QueryParam(name="building_name", map=true, nullable=false, description="filter by building name. example: building_name[eq]=value")
     *
     * @Rest\View(serializerGroups={"room", "response", "short"})
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
     * @SWG\Tag(name="rooms")
     * @param ParamFetcherInterface $paramFetcher
     * @param RoomRepository $roomRepository
     * @return array
     * @Rest\View()
     */
    public function findRoomsLevelByCriteria(ParamFetcherInterface $paramFetcher,RoomRepository $roomRepository){
        $batiment =  $paramFetcher->get('batiment')??"[]";
        return $roomRepository->findRoomsLevelByCriteria($batiment);
    }

    /**
     * @Rest\Get("/findRoomsRefByCriteria")
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
     * @SWG\Parameter(
     *     name="level",
     *     type="string",
     *     in="query",
     *     description="level"
     * )
     * @Rest\QueryParam(name="batiment", nullable=true, default="", description="commune id")
     * @Rest\QueryParam(name="level", nullable=true, default="", description="level")
     * @SWG\Tag(name="rooms")
     * @param ParamFetcherInterface $paramFetcher
     * @param RoomRepository $roomRepository
     * @return array
     * @Rest\View()
     */
    public function findRoomsRefByCriteria(ParamFetcherInterface $paramFetcher,RoomRepository $roomRepository){
        $batiment =  $paramFetcher->get('batiment');
        $level =  $paramFetcher->get('level');
        return $roomRepository->findRoomsRefByCriteria($batiment,$level);
    }

    /**
     * @Rest\Post("/")
     *
     * @SWG\Response(
     *     response=201,
     *     description="Returns created Room",
     *     @SWG\Schema(
     *         ref=@Model(type=Room::class, groups={"room"})
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Creation error"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Add Room",
     *     @Model(type=Room::class, groups={"room"})
     * )
     * @SWG\Tag(name="rooms")
     *
     * @Rest\View(serializerGroups={"room"})
     *
     * @param Request $request
     *
     * @return View
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function postRoom(Request $request)
    {
        $form = $this->createForm(RoomType::class);
        $form->submit($request->request->all());
        if ($form->isValid()) {
            $room = $this->apiManager->save($form->getData());
            return $this->view($room, Response::HTTP_CREATED);
        } else {
            throw new FormValidationException($form);
        }
    }

    /**
     * @Rest\Put("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=204,
     *     description="Room is updated"
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Update error"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Update a Room",
     *     @Model(type=Room::class, groups={"room"})
     * )
     * @SWG\Tag(name="rooms")
     *
     * @Rest\View()
     *
     * @param Request $request
     * @param Room $room
     *
     * @return View
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateRoom(Request $request, Room $room)
    {
        $form = $this->createForm(RoomType::class, $room);
        $form->submit($request->request->all(), false);

        if ($form->isValid()) {
            $this->apiManager->save($room);
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
     *     description="Room is removed"
     *     )
     * )
     * @SWG\Tag(name="rooms")
     *
     * @Rest\View()
     *
     * @param Room $room
     *
     * @return View
     */
    public function removeRoom(Room $room)
    {
        $this->apiManager->delete($room);
        return $this->view(null, Response::HTTP_NO_CONTENT);
    }

}