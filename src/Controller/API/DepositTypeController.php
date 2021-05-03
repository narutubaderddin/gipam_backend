<?php

namespace App\Controller\API;

use App\Entity\DepositType;
use App\Exception\FormValidationException;
use App\Form\DepositTypeType;
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
 * Class DepositTypeController
 * @package App\Controller\API
 * @Route("/depositTypes")
 */
class DepositTypeController extends AbstractFOSRestController
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
     *     description="Returns DepositType by id",
     *     @SWG\Schema(
     *         ref=@Model(type=DepositType::class, groups={"deposit_type"})
     *     )
     * )
     * @SWG\Tag(name="depositTypes")
     * @Rest\View(serializerGroups={"deposit_type"})
     *
     * @param DepositType $depositType
     *
     * @return Response
     */
    public function showDepositType(DepositType $depositType)
    {
        return $this->view($depositType, Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of an DepositType",
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
     * @SWG\Tag(name="depositTypes")
     *
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="page number.")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="0", description="page size.")
     * @Rest\QueryParam(name="sort_by", nullable=true, default="id", description="order by")
     * @Rest\QueryParam(name="sort", requirements="(asc|desc)", nullable=true, default="asc", description="tri order asc|desc")
     * @Rest\QueryParam(name="label", map=true, nullable=false, description="filter by label. example: label[eq]=value")
     * @Rest\QueryParam(name="active", map=true, nullable=false, description="filter by active. example: active[eq]=1")
     * @Rest\QueryParam(name="search", map=false, nullable=true, description="search. example: search=text")
     *
     * @Rest\View()
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return Response
     */
    public function listDepositTypes(ParamFetcherInterface $paramFetcher)
    {
        $records = $this->apiManager->findRecordsByEntityName(DepositType::class, $paramFetcher);
        return $this->view($records, Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/")
     *
     * @SWG\Response(
     *     response=201,
     *     description="Returns created DepositType",
     *     @SWG\Schema(
     *         ref=@Model(type=DepositType::class, groups={"deposit_type"})
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Creation error"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Add DepositType",
     *     @Model(type=DepositType::class, groups={"deposit_type"})
     * )
     * @SWG\Tag(name="depositTypes")
     *
     * @Rest\View(serializerGroups={"deposit_type"})
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function postDepositType(Request $request)
    {
        $form = $this->createForm(DepositTypeType::class);
        $form->submit($request->request->all());
        if ($form->isValid()) {
            $depositType = $this->apiManager->save($form->getData());
            return $this->view($depositType, Response::HTTP_CREATED);
        } else {
            throw new FormValidationException($form);
        }
    }

    /**
     * @Rest\Put("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=204,
     *     description="DepositType is updated"
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Update error"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Update a DepositType",
     *     @Model(type=DepositType::class, groups={"deposit_type"})
     * )
     * @SWG\Tag(name="depositTypes")
     *
     * @Rest\View()
     *
     * @param Request $request
     * @param DepositType $depositType
     *
     * @return Response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateDepositType(Request $request, DepositType $depositType)
    {
        $form = $this->createForm(DepositTypeType::class, $depositType);
        $form->submit($request->request->all(), false);

        if ($form->isValid()) {
            $this->apiManager->save($depositType);
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
     *     description="DepositType is removed"
     *     )
     * )
     * @SWG\Tag(name="depositTypes")
     *
     * @Rest\View()
     *
     * @param DepositType $depositType
     *
     * @return Response
     */
    public function removeDepositType(DepositType $depositType)
    {
        $this->apiManager->delete($depositType);
        return $this->view(null, Response::HTTP_NO_CONTENT);
    }
}