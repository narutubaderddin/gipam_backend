<?php


namespace App\Controller\API;


use App\Entity\Era;
use App\Exception\FormValidationException;
use App\Form\EraType;
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
 * Class EraController
 * @package App\Controller\API
 * @Route("/eras")
 */
class EraController extends AbstractFOSRestController
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
     *     description="Returns Era by id",
     *     @SWG\Schema(
     *         ref=@Model(type=Era::class, groups={"era"})
     *     )
     * )
     * @SWG\Tag(name="eras")
     * @Rest\View(serializerGroups={"era"})
     *
     * @param Era $era
     *
     * @return Response
     */
    public function showEra(Era $era)
    {
        return $this->view($era, Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of an Era",
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
     *     description="The fiemld used to sort type"
     * )
     * @SWG\Parameter(
     *     name="label",
     *     in="query",
     *     type="string",
     *     description="The field used to filter by label"
     * )
     * @SWG\Tag(name="eras")
     *
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="page number.")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="20", description="page size.")
     * @Rest\QueryParam(name="sort_by", nullable=true, default="id", description="order by")
     * @Rest\QueryParam(name="sort", requirements="(asc|desc)", nullable=true, default="asc", description="tri order asc|desc")
     * @Rest\QueryParam(name="label",map=true, nullable=false, description="filter by label. example: label[eq]=value")
     * @Rest\QueryParam(name="active", nullable=false, description="filter by active. example: active[eq]=1")
     *
     * @Rest\View()
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return Response
     */
    public function listEras(ParamFetcherInterface $paramFetcher)
    {
        $records = $this->apiManager->findRecordsByEntityName(Era::class, $paramFetcher);
        return $this->view($records, Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/")
     *
     * @SWG\Response(
     *     response=201,
     *     description="Returns created Era",
     *     @SWG\Schema(
     *         ref=@Model(type=Era::class, groups={"era"})
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Creation error"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Add Era",
     *     @Model(type=Era::class, groups={"era"})
     * )
     * @SWG\Tag(name="eras")
     *
     * @Rest\View(serializerGroups={"era"})
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function postEra(Request $request)
    {
        $form = $this->createForm(EraType::class);
        $form->submit($request->request->all());
        if ($form->isValid()) {
            $era = $this->apiManager->save($form->getData());
            return $this->view($era, Response::HTTP_CREATED);
        } else {
            throw new FormValidationException($form);
        }
    }

    /**
     * @Rest\Put("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=204,
     *     description="Era is updated"
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Update error"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Update a Era",
     *     @Model(type=Era::class, groups={"era"})
     * )
     * @SWG\Tag(name="eras")
     *
     * @Rest\View()
     *
     * @param Request $request
     * @param Era $era
     *
     * @return Response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateEra(Request $request, Era $era)
    {
        $form = $this->createForm(EraType::class, $era);
        $form->submit($request->request->all(), false);

        if ($form->isValid()) {
            $this->apiManager->save($era);
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
     *     description="Era is removed"
     *     )
     * )
     * @SWG\Tag(name="eras")
     *
     * @Rest\View()
     *
     * @param Era $era
     *
     * @return Response
     */
    public function removeEra(Era $era)
    {
        $this->apiManager->delete($era);
        return $this->view(null, Response::HTTP_NO_CONTENT);
    }
}