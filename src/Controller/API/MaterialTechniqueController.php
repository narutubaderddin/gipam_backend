<?php


namespace App\Controller\API;


use App\Entity\MaterialTechnique;
use App\Form\MaterialTechniqueType;
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
     *     description="The fiemld used to sort type"
     * )
     * @SWG\Parameter(
     *     name="label",
     *     in="query",
     *     type="string",
     *     description="The field used to filter by label"
     * )
     * @SWG\Tag(name="materialTechniques")
     *
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="page number.")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="20", description="page size.")
     * @Rest\QueryParam(name="sort_by", nullable=true, default="id", description="order by")
     * @Rest\QueryParam(name="sort", requirements="(asc|desc)", nullable=true, default="asc", description="tri order asc|desc")
     * @Rest\QueryParam(name="label",map=true, nullable=false, description="filter by label. example: label[eq]=value")
     * @Rest\QueryParam(name="type",map=true, nullable=false, description="filter by type. example: type[eq]=value")
     * @Rest\QueryParam(name="active", nullable=false, description="filter by active. example: active[eq]=1")
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
            return $this->view($form, Response::HTTP_BAD_REQUEST);
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
            return $this->view($form, Response::HTTP_BAD_REQUEST);
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