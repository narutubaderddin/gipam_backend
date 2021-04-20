<?php

namespace App\Controller\API;

use App\Entity\SubDivision;
use App\Exception\FormValidationException;
use App\Form\SubDivisionType;
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
 * Class SubDivisionController
 * @package App\Controller\API
 * @Route("/subDivisions")
 */
class SubDivisionController extends AbstractFOSRestController
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
     *     description="Returns SubDivision by id",
     *     @SWG\Schema(
     *         ref=@Model(type=SubDivision::class, groups={"sub_division", "establishment_id"})
     *     )
     * )
     * @SWG\Tag(name="subDivisions")
     * @Rest\View(serializerGroups={"sub_division", "establishment_id"})
     *
     * @param SubDivision $subDivision
     *
     * @return Response
     */
    public function showSubDivision(SubDivision $subDivision)
    {
        return $this->view($subDivision, Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of an SubDivision",
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
     * @SWG\Tag(name="subDivisions")
     *
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="page number.")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="20", description="page size.")
     * @Rest\QueryParam(name="sort_by", nullable=true, default="id", description="order by")
     * @Rest\QueryParam(name="sort", requirements="(asc|desc)", nullable=true, default="asc", description="tri order asc|desc")
     * @Rest\QueryParam(name="label", map=true, nullable=false, description="filter by label. example: label[eq]=value")
     * @Rest\QueryParam(name="acronym", map=true, nullable=false, description="filter by acronym. example: acronym[eq]=value")
     * @Rest\QueryParam(name="startDate", map=true, nullable=false, description="filter by startDate. example: startDate[lt]=value")
     * @Rest\QueryParam(name="endDate", map=true, nullable=false, description="filter by endDate. example: endDate[lt]=value")
     * @Rest\QueryParam(name="establishment", map=true, nullable=false, description="filter by establishment. example: establishment[eq]=value")
     *
     * @Rest\View()
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return Response
     */
    public function listSubDivisions(ParamFetcherInterface $paramFetcher)
    {
        $records = $this->apiManager->findRecordsByEntityName(SubDivision::class, $paramFetcher);
        return $this->view($records, Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/")
     *
     * @SWG\Response(
     *     response=201,
     *     description="Returns created SubDivision",
     *     @SWG\Schema(
     *         ref=@Model(type=SubDivision::class, groups={"sub_division", "establishment_id"})
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Creation error"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Add SubDivision",
     *     @Model(type=SubDivision::class, groups={"sub_division"})
     * )
     * @SWG\Tag(name="subDivisions")
     *
     * @Rest\View(serializerGroups={"sub_division", "establishment_id"})
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function postSubDivision(Request $request)
    {
        $form = $this->createForm(SubDivisionType::class);
        $form->submit($request->request->all());
        if ($form->isValid()) {
            $subDivision = $this->apiManager->save($form->getData());
            return $this->view($subDivision, Response::HTTP_CREATED);
        } else {
            throw new FormValidationException($form);
        }
    }

    /**
     * @Rest\Put("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=204,
     *     description="SubDivision is updated"
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Update error"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Update a SubDivision",
     *     @Model(type=SubDivision::class, groups={"sub_division"})
     * )
     * @SWG\Tag(name="subDivisions")
     *
     * @Rest\View()
     *
     * @param Request $request
     * @param SubDivision $subDivision
     *
     * @return Response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateSubDivision(Request $request, SubDivision $subDivision)
    {
        $form = $this->createForm(SubDivisionType::class, $subDivision);
        $form->submit($request->request->all(), false);

        if ($form->isValid()) {
            $this->apiManager->save($subDivision);
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
     *     description="SubDivision is removed"
     *     )
     * )
     * @SWG\Tag(name="subDivisions")
     *
     * @Rest\View()
     *
     * @param SubDivision $subDivision
     *
     * @return Response
     */
    public function removeMinistry(SubDivision $subDivision)
    {
        $this->apiManager->delete($subDivision);
        return $this->view(null, Response::HTTP_NO_CONTENT);
    }
}