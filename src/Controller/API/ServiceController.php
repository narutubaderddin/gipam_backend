<?php

namespace App\Controller\API;

use App\Entity\Service;
use App\Exception\FormValidationException;
use App\Form\ServiceType;
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
 * Class ServiceController
 * @package App\Controller\API
 * @Route("/services")
 */
class ServiceController extends AbstractFOSRestController
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
     *     description="Returns Service by id",
     *     @SWG\Schema(
     *         ref=@Model(type=Service::class, groups={"service", "sub_division_id"})
     *     )
     * )
     * @SWG\Tag(name="services")
     * @Rest\View(serializerGroups={"service", "sub_division_id"})
     *
     * @param Service $service
     *
     * @return Response
     */
    public function showService(Service $service)
    {
        return $this->view($service, Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of an Service",
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
     * @SWG\Tag(name="services")
     *
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="page number.")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="0", description="page size.")
     * @Rest\QueryParam(name="sort_by", nullable=true, default="id", description="order by")
     * @Rest\QueryParam(name="sort", requirements="(asc|desc)", nullable=true, default="asc", description="tri order asc|desc")
     * @Rest\QueryParam(name="label", map=true, nullable=false, description="filter by label. example: label[eq]=value")
     * @Rest\QueryParam(name="acronym", map=true, nullable=false, description="filter by acronym. example: acronym[eq]=value")
     * @Rest\QueryParam(name="startDate", map=true, nullable=false, description="filter by startDate. example: startDate[lt]=value")
     * @Rest\QueryParam(name="disappearanceDate", map=true, nullable=false, description="filter by disappearanceDate. example: disappearanceDate[lt]=value")
     * @Rest\QueryParam(name="subDivision", map=true, nullable=false, description="filter by subDivision. example: subDivision[eq]=value")
     * @Rest\QueryParam(name="search", map=false, nullable=true, description="search. example: search=text")
     *
     * @Rest\View()
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return Response
     */
    public function listServices(ParamFetcherInterface $paramFetcher)
    {
        $records = $this->apiManager->findRecordsByEntityName(Service::class, $paramFetcher);
        return $this->view($records, Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/")
     *
     * @SWG\Response(
     *     response=201,
     *     description="Returns created Service",
     *     @SWG\Schema(
     *         ref=@Model(type=Service::class, groups={"service", "sub_division_id"})
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Creation error"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Add Service",
     *     @Model(type=Service::class, groups={"service"})
     * )
     * @SWG\Tag(name="services")
     *
     * @Rest\View(serializerGroups={"service", "sub_division_id"})
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function postService(Request $request)
    {
        $form = $this->createForm(ServiceType::class);
        $form->submit($request->request->all());
        if ($form->isValid()) {
            $service = $this->apiManager->save($form->getData());
            return $this->view($service, Response::HTTP_CREATED);
        } else {
            throw new FormValidationException($form);
        }
    }

    /**
     * @Rest\Put("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=204,
     *     description="Service is updated"
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Update error"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Update a Service",
     *     @Model(type=Service::class, groups={"service"})
     * )
     * @SWG\Tag(name="services")
     *
     * @Rest\View()
     *
     * @param Request $request
     * @param Service $service
     *
     * @return Response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateService(Request $request, Service $service)
    {
        $form = $this->createForm(ServiceType::class, $service);
        $form->submit($request->request->all(), false);

        if ($form->isValid()) {
            $this->apiManager->save($service);
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
     *     description="Service is removed"
     *     )
     * )
     * @SWG\Tag(name="services")
     *
     * @Rest\View()
     *
     * @param Service $service
     *
     * @return Response
     */
    public function removeService(Service $service)
    {
        $this->apiManager->delete($service);
        return $this->view(null, Response::HTTP_NO_CONTENT);
    }
}