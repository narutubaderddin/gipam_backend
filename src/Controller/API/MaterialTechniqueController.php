<?php

namespace App\Controller\API;

use App\Entity\MaterialTechnique;
use App\Exception\FormValidationException;
use App\Form\MaterialTechniqueType;
use App\Model\ApiResponse;
use App\Services\ApiManager;
use App\Services\MaterialTechniqueService;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Swagger\Annotations as SWG;

/**
 * Class MaterialTechniqueController
 * @package App\Controller\API
 * @Route("/materialTechniques")
 */
class MaterialTechniqueController extends AbstractFOSRestController
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
     *     description="Returns MaterialTechnique by id",
     *     @SWG\Schema(
     *         ref=@Model(type=MaterialTechnique::class, groups={"material_technique", "denomination_id"})
     *     )
     * )
     * @SWG\Tag(name="materialTechniques")
     * @Rest\View(serializerGroups={"material_technique", "denomination_id"})
     *
     * @param MaterialTechnique $materialTechnique
     *
     * @return Response
     */
    public function showMaterialTechnique(MaterialTechnique $materialTechnique)
    {
        return $this->view($materialTechnique, Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of an MaterialTechnique",
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
     * @SWG\Tag(name="materialTechniques")
     *
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="page number.")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="0", description="page size.")
     * @Rest\QueryParam(name="sort_by", nullable=true, default="id", description="order by")
     * @Rest\QueryParam(name="sort", requirements="(asc|desc)", nullable=true, default="asc", description="tri order asc|desc")
     * @Rest\QueryParam(name="label",map=true, nullable=false, description="filter by label. example: label[eq]=value")
     * @Rest\QueryParam(name="type",map=true, nullable=false, description="filter by type. example: type[eq]=value")
     * @Rest\QueryParam(name="active" ,map=true, nullable=false, description="filter by active. example: active[eq]=1")
     * @Rest\QueryParam(name="denominations", map=true, nullable=false, description="filter by denomination. denominations: field[eq]=1")
     * @Rest\QueryParam(name="denominations_id", map=true, nullable=false, description="filter by denominations id. denominations_id[eq]=1")
     * @Rest\QueryParam(name="search", map=false, nullable=true, description="search. example: search=text")
     *
     * @Rest\View()
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return Response
     */
    public function listMaterialTechniques(ParamFetcherInterface $paramFetcher)
    {
        $records = $this->apiManager->findRecordsByEntityName(MaterialTechnique::class, $paramFetcher);
        return $this->view($records, Response::HTTP_OK);
    }

    /**
     *
     * @Rest\Get("/findByCriteria")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of an MaterialTechnique filtred by Field and denomination",
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
     *     name="field_id",
     *     in="query",
     *     type="integer",
     *     description="field id"
     * )
     * @SWG\Parameter(
     *     name="denomination_id",
     *     in="query",
     *     type="integer",
     *     description="denomination id"
     * )
     *
     * @SWG\Tag(name="materialTechniques")
     *
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="page number.")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="0", description="page size.")
     * @Rest\QueryParam(name="fields", nullable=true, default="", description="field_id")
     * @Rest\QueryParam(name="denominations", nullable=true, default="", description="denomination_id")
     *
     * @Rest\View()
     *
     * @param ParamFetcherInterface $paramFetcher
     * @param ApiManager $apiManager
     * @return View
     */
    public function listMaterialTechniqueByCriteria(ParamFetcherInterface $paramFetcher,ApiManager $apiManager){
        $records =$apiManager->findRecordsByEntityNameAndCriteria(MaterialTechnique::class,$paramFetcher);
        return $this->view($records, Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/")
     *
     * @SWG\Response(
     *     response=201,
     *     description="Returns created MaterialTechnique",
     *     @SWG\Schema(
     *         ref=@Model(type=MaterialTechnique::class, groups={"material_technique", "denomination_id"})
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Creation error"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Add MaterialTechnique",
     *     @Model(type=MaterialTechnique::class, groups={"material_technique"})
     * )
     * @SWG\Tag(name="materialTechniques")
     *
     * @Rest\View(serializerGroups={"material_technique", "denomination_id"})
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function postMaterialTechnique(Request $request)
    {
        $form = $this->createForm(MaterialTechniqueType::class);
        $form->submit($request->request->all());
        if ($form->isValid()) {
            $materialTechnique = $this->apiManager->save($form->getData());
            return $this->view($materialTechnique, Response::HTTP_CREATED);
        } else {
            throw new FormValidationException($form);
        }
    }

    /**
     * @Rest\Put("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=204,
     *     description="MaterialTechnique is updated"
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Update error"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Update a MaterialTechnique",
     *     @Model(type=MaterialTechnique::class, groups={"material_technique"})
     * )
     * @SWG\Tag(name="materialTechniques")
     *
     * @Rest\View()
     *
     * @param Request $request
     * @param MaterialTechnique $materialTechnique
     *
     * @return Response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateMaterialTechnique(Request $request, MaterialTechnique $materialTechnique)
    {
        $form = $this->createForm(MaterialTechniqueType::class, $materialTechnique);
        $form->submit($request->request->all(), false);

        if ($form->isValid()) {
            $this->apiManager->save($materialTechnique);
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
     *     description="MaterialTechnique is removed"
     *     )
     * )
     * @SWG\Tag(name="materialTechniques")
     *
     * @Rest\View()
     *
     * @param MaterialTechnique $materialTechnique
     *
     * @return Response
     */
    public function removeMaterialTechnique(MaterialTechnique $materialTechnique)
    {
        $this->apiManager->delete($materialTechnique);
        return $this->view(null, Response::HTTP_NO_CONTENT);
    }
}
