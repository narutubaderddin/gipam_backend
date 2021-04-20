<?php

namespace App\Controller\API;

use App\Entity\Establishment;
use App\Exception\FormValidationException;
use App\Form\EstablishmentType;
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
 * Class EstablishmentController
 * @package App\Controller\API
 * @Route("/establishments")
 */
class EstablishmentController extends AbstractFOSRestController
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
     *     description="Returns Establishment by id",
     *     @SWG\Schema(
     *         ref=@Model(type=Establishment::class, groups={"establishment", "ministry_id", "establishment_type_id"})
     *     )
     * )
     * @SWG\Tag(name="establishments")
     * @Rest\View(serializerGroups={"establishment", "ministry_id", "establishment_type_id"})
     *
     * @param Establishment $establishment
     *
     * @return Response
     */
    public function showEstablishment(Establishment $establishment)
    {
        return $this->view($establishment, Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of an Establishment",
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
     * @SWG\Tag(name="establishments")
     *
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="page number.")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="20", description="page size.")
     * @Rest\QueryParam(name="sort_by", nullable=true, default="id", description="order by")
     * @Rest\QueryParam(name="sort", requirements="(asc|desc)", nullable=true, default="asc", description="tri order asc|desc")
     * @Rest\QueryParam(name="label", map=true, nullable=false, description="filter by label. example: label[eq]=value")
     * @Rest\QueryParam(name="acronym", map=true, nullable=false, description="filter by acronym. example: acronym[eq]=value")
     * @Rest\QueryParam(name="startDate", map=true, nullable=false, description="filter by startDate. example: startDate[lt]=value")
     * @Rest\QueryParam(name="disappearanceDate", map=true, nullable=false, description="filter by disappearanceDate. example: disappearanceDate[lt]=value")
     * @Rest\QueryParam(name="ministry", map=true, nullable=false, description="filter by ministry. example: ministry[eq]=value")
     * @Rest\QueryParam(name="type", map=true, nullable=false, description="filter by type. example: type[eq]=value")
     *
     * @Rest\View()
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return Response
     */
    public function listEstablishments(ParamFetcherInterface $paramFetcher)
    {
        $records = $this->apiManager->findRecordsByEntityName(Establishment::class, $paramFetcher);
        return $this->view($records, Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/")
     *
     * @SWG\Response(
     *     response=201,
     *     description="Returns created Establishment",
     *     @SWG\Schema(
     *         ref=@Model(type=Establishment::class, groups={"establishment", "ministry_id", "establishment_type_id"})
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Creation error"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Add Establishment",
     *     @Model(type=Establishment::class, groups={"establishment"})
     * )
     * @SWG\Tag(name="establishments")
     *
     * @Rest\View(serializerGroups={"establishment", "ministry_id", "establishment_type_id"})
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function postEstablishment(Request $request)
    {
        $form = $this->createForm(EstablishmentType::class);
        $form->submit($request->request->all());
        if ($form->isValid()) {
            $establishment = $this->apiManager->save($form->getData());
            return $this->view($establishment, Response::HTTP_CREATED);
        } else {
            throw new FormValidationException($form);
        }
    }

    /**
     * @Rest\Put("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=204,
     *     description="Establishment is updated"
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Update error"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Update a Establishment",
     *     @Model(type=Establishment::class, groups={"establishment"})
     * )
     * @SWG\Tag(name="establishments")
     *
     * @Rest\View()
     *
     * @param Request $request
     * @param Establishment $establishment
     *
     * @return Response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateEstablishment(Request $request, Establishment $establishment)
    {
        $form = $this->createForm(EstablishmentType::class, $establishment);
        $form->submit($request->request->all(), false);

        if ($form->isValid()) {
            $this->apiManager->save($establishment);
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
     *     description="Establishment is removed"
     *     )
     * )
     * @SWG\Tag(name="establishments")
     *
     * @Rest\View()
     *
     * @param Establishment $establishment
     *
     * @return Response
     */
    public function removeMinistry(Establishment $establishment)
    {
        $this->apiManager->delete($establishment);
        return $this->view(null, Response::HTTP_NO_CONTENT);
    }
}