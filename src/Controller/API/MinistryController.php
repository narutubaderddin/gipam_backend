<?php

namespace App\Controller\API;

use App\Entity\Ministry;
use App\Exception\FormValidationException;
use App\Form\MinistryType;
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
 * Class MinistryController
 * @package App\Controller\API
 * @Route("/ministries")
 */
class MinistryController extends AbstractFOSRestController
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
     *     description="Returns Ministry by id",
     *     @SWG\Schema(
     *         ref=@Model(type=Ministry::class, groups={"ministry"})
     *     )
     * )
     * @SWG\Tag(name="ministries")
     * @Rest\View(serializerGroups={"ministry"})
     *
     * @param Ministry $ministry
     *
     * @return Response
     */
    public function showMinistry(Ministry $ministry)
    {
        return $this->view($ministry, Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of an Ministry",
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
     * @SWG\Tag(name="ministries")
     *
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="page number.")
     * @Rest\QueryParam(name="limit", requirements="\d+", nullable=true, description="page size.")
     * @Rest\QueryParam(name="sort_by", nullable=true, default="id", description="order by")
     * @Rest\QueryParam(name="sort", requirements="(asc|desc)", nullable=true, default="asc", description="tri order asc|desc")
     * @Rest\QueryParam(name="name", map=true, nullable=false, description="filter by name. example: name[eq]=value")
     * @Rest\QueryParam(name="acronym", map=true, nullable=false, description="filter by acronym. example: acronym[eq]=value")
     * @Rest\QueryParam(name="startDate", map=true, nullable=false, description="filter by startDate. example: startDate[lt]=value")
     * @Rest\QueryParam(name="disappearanceDate", map=true, nullable=false, description="filter by disappearanceDate. example: disappearanceDate[lt]=value")
     * @Rest\QueryParam(name="search", map=false, nullable=true, description="search. example: search=text")
     *
     * @Rest\View()
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return Response
     */
    public function listMinistries(ParamFetcherInterface $paramFetcher)
    {
        $records = $this->apiManager->findRecordsByEntityName(Ministry::class, $paramFetcher);
        return $this->view($records, Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/")
     *
     * @SWG\Response(
     *     response=201,
     *     description="Returns created Ministry",
     *     @SWG\Schema(
     *         ref=@Model(type=Ministry::class, groups={"ministry"})
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Creation error"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Add Ministry",
     *     @Model(type=Ministry::class, groups={"ministry"})
     * )
     * @SWG\Tag(name="ministries")
     *
     * @Rest\View(serializerGroups={"ministry"})
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function postMinistry(Request $request)
    {
        $form = $this->createForm(MinistryType::class);
        $form->submit($request->request->all());
        if ($form->isValid()) {
            $ministry = $this->apiManager->save($form->getData());
            return $this->view($ministry, Response::HTTP_CREATED);
        } else {
            throw new FormValidationException($form);
        }
    }

    /**
     * @Rest\Put("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=204,
     *     description="Ministry is updated"
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Update error"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Update a Ministry",
     *     @Model(type=Ministry::class, groups={"ministry"})
     * )
     * @SWG\Tag(name="ministries")
     *
     * @Rest\View()
     *
     * @param Request $request
     * @param Ministry $ministry
     *
     * @return Response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateMinistry(Request $request, Ministry $ministry)
    {
        $form = $this->createForm(MinistryType::class, $ministry);
        $form->submit($request->request->all(), false);

        if ($form->isValid()) {
            $this->apiManager->save($ministry);
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
     *     description="Ministry is removed"
     *     )
     * )
     * @SWG\Tag(name="ministries")
     *
     * @Rest\View()
     *
     * @param Ministry $ministry
     *
     * @return Response
     */
    public function removeMinistry(Ministry $ministry)
    {
        $this->apiManager->delete($ministry);
        return $this->view(null, Response::HTTP_NO_CONTENT);
    }
}