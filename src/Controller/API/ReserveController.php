<?php


namespace App\Controller\API;


use App\Entity\Reserve;
use App\Exception\FormValidationException;
use App\Form\ReserveType;
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
 * Class ReserveController
 * @package App\Controller\API
 * @Rest\Route("/reserves")
 */
class ReserveController extends AbstractFOSRestController
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
     *     description="Returns Reserve by id",
     *     @SWG\Schema(
     *         ref=@Model(type=Reserve::class, groups={"reserve"})
     *     )
     * )
     * @SWG\Tag(name="reserves")
     * @Rest\View(serializerGroups={"reserve", "short"})
     *
     * @param Reserve $reserve
     *
     * @return View
     */
    public function showReserve(Reserve $reserve)
    {
        return $this->view($reserve, Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of reserves",
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
     * @SWG\Tag(name="reserves")
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
     * @Rest\QueryParam(name="label", map=true, nullable=false, description="filter by label. example: label[eq]=value")
     * @Rest\QueryParam(name="startDate", map=true, nullable=false, description="filter by startDate. example: startDate[lt]=value")
     * @Rest\QueryParam(name="endDate", map=true, nullable=false, description="filter by endDate. example: endDate[lt]=value")
     * @Rest\QueryParam(name="room", nullable=false, description="filter by room id. example: room[eq]=value")
     * @Rest\QueryParam(name="room_reference", nullable=false, description="filter by room reference. example: room_reference[eq]=value")
     *
     * @Rest\View(serializerGroups={"reserve", "response", "short"})
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return View
     */
    public function listOfReserves(ParamFetcherInterface $paramFetcher)
    {
        $records = $this->apiManager->findRecordsByEntityName(Reserve::class, $paramFetcher);
        return $this->view($records, Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/")
     *
     * @SWG\Response(
     *     response=201,
     *     description="Returns created Reserve",
     *     @SWG\Schema(
     *         ref=@Model(type=Reserve::class, groups={"reserve"})
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Creation error"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Add Reserve",
     *     @Model(type=Reserve::class, groups={"reserve"})
     * )
     * @SWG\Tag(name="reserves")
     *
     * @Rest\View(serializerGroups={"reserve", "short"})
     *
     * @param Request $request
     *
     * @return View
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function postReserve(Request $request)
    {
        $form = $this->createForm(ReserveType::class);
        $form->submit($request->request->all());
        if ($form->isValid()) {
            $reserve = $this->apiManager->save($form->getData());
            return $this->view($reserve, Response::HTTP_CREATED);
        }
        throw new FormValidationException($form);
    }

    /**
     * @Rest\Put("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=204,
     *     description="Reserve is updated"
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Update error"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Update a Reserve",
     *     @Model(type=Reserve::class, groups={"reserve"})
     * )
     * @SWG\Tag(name="reserves")
     *
     * @Rest\View()
     *
     * @param Request $request
     * @param Reserve $reserve
     *
     * @return View
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateReserve(Request $request, Reserve $reserve)
    {
        $form = $this->createForm(ReserveType::class, $reserve);
        $form->submit($request->request->all(), false);

        if ($form->isValid()) {
            $this->apiManager->save($reserve);
            return $this->view(null, Response::HTTP_NO_CONTENT);
        }
        throw new FormValidationException($form);
    }

    /**
     * @Rest\Delete("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=204,
     *     description="Reserve is removed"
     *     )
     * )
     * @SWG\Tag(name="reserves")
     *
     * @Rest\View()
     *
     * @param Reserve $reserve
     *
     * @return View
     */
    public function removeReserve(Reserve $reserve)
    {
        $this->apiManager->delete($reserve);
        return $this->view(null, Response::HTTP_NO_CONTENT);
    }

}