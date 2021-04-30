<?php

namespace App\Controller\API;

use App\Entity\Denomination;
use App\Exception\FormValidationException;
use App\Form\DenominationType;
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
 * Class DenominationController
 * @package App\Controller\API
 * @Route("/denominations")
 */
class DenominationController extends AbstractFOSRestController
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
     *     description="Returns Denomination by id",
     *     @SWG\Schema(
     *         ref=@Model(type=Denomination::class, groups={"denomination", "field_id"})
     *     )
     * )
     * @SWG\Tag(name="denominations")
     * @Rest\View(serializerGroups={"denomination", "field_id"})
     *
     * @param Denomination $denomination
     *
     * @return Response
     */
    public function showDenomination(Denomination $denomination)
    {
        return $this->view($denomination, Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of an Denomination",
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
     * @SWG\Tag(name="denominations")
     *
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="page number.")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="0", description="page size.")
     * @Rest\QueryParam(name="sort_by", nullable=true, default="id", description="order by")
     * @Rest\QueryParam(name="sort", requirements="(asc|desc)", nullable=true, default="asc", description="tri order asc|desc")
     * @Rest\QueryParam(name="label",map=true, nullable=false, description="filter by label. example: label[eq]=value")
     * @Rest\QueryParam(name="active", map=true, nullable=false, description="filter by active. example: active[eq]=1")
     * @Rest\QueryParam(name="field", map=true, nullable=false, description="filter by field. example: field[eq]=1")
     *
     * @Rest\View()
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return Response
     */
    public function listDenominations(ParamFetcherInterface $paramFetcher)
    {
        $records = $this->apiManager->findRecordsByEntityName(Denomination::class, $paramFetcher);
        return $this->view($records, Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/")
     *
     * @SWG\Response(
     *     response=201,
     *     description="Returns created Denomination",
     *     @SWG\Schema(
     *         ref=@Model(type=Denomination::class, groups={"denomination", "field_id"})
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Creation error"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Add Denomination",
     *     @Model(type=Denomination::class, groups={"denomination"})
     * )
     * @SWG\Tag(name="denominations")
     *
     * @Rest\View(serializerGroups={"denomination", "field_id"})
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function postDenomination(Request $request)
    {
        $form = $this->createForm(DenominationType::class);
        $form->submit($request->request->all());
        if ($form->isValid()) {
            $denomination = $this->apiManager->save($form->getData());
            return $this->view($denomination, Response::HTTP_CREATED);
        } else {
            throw new FormValidationException($form);
        }
    }

    /**
     * @Rest\Put("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=204,
     *     description="Denomination is updated"
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Update error"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Update a Denomination",
     *     @Model(type=Denomination::class, groups={"denomination"})
     * )
     * @SWG\Tag(name="denominations")
     *
     * @Rest\View()
     *
     * @param Request $request
     * @param Denomination $denomination
     *
     * @return Response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateDenomination(Request $request, Denomination $denomination)
    {
        $form = $this->createForm(DenominationType::class, $denomination);
        $form->submit($request->request->all(), false);

        if ($form->isValid()) {
            $this->apiManager->save($denomination);
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
     *     description="Denomination is removed"
     *     )
     * )
     * @SWG\Tag(name="denominations")
     *
     * @Rest\View()
     *
     * @param Denomination $denomination
     *
     * @return Response
     */
    public function removeDenomination(Denomination $denomination)
    {
        $this->apiManager->delete($denomination);
        return $this->view(null, Response::HTTP_NO_CONTENT);
    }
}