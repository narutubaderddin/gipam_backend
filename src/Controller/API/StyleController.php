<?php


namespace App\Controller\API;


use App\Entity\Style;
use App\Exception\FormValidationException;
use App\Form\StyleType;
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
 * Class StyleController
 * @package App\Controller\API
 * @Route("/styles")
 */
class StyleController extends AbstractFOSRestController
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
     *     description="Returns Style by id",
     *     @SWG\Schema(
     *         ref=@Model(type=Style::class, groups={"style"})
     *     )
     * )
     * @SWG\Tag(name="styles")
     * @Rest\View(serializerGroups={"style"})
     *
     * @param Style $style
     *
     * @return Response
     */
    public function showStyle(Style $style)
    {
        return $this->view($style, Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of an Style",
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
     *
     * @SWG\Tag(name="styles")
     *
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="page number.")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="20", description="page size.")
     * @Rest\QueryParam(name="sort_by", nullable=true, default="id", description="order by")
     * @Rest\QueryParam(name="sort", requirements="(asc|desc)", nullable=true, default="asc", description="tri order asc|desc")
     * @Rest\QueryParam(name="label",map=true, nullable=false, description="filter by label. example: label[eq]=value")
     * @Rest\QueryParam(name="active", map=true, nullable=false, description="filter by active. example: active[eq]=1")
     *
     * @Rest\View()
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return Response
     */
    public function listStyles(ParamFetcherInterface $paramFetcher)
    {
        $records = $this->apiManager->findRecordsByEntityName(Style::class, $paramFetcher);
        return $this->view($records, Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/")
     *
     * @SWG\Response(
     *     response=201,
     *     description="Returns created Style",
     *     @SWG\Schema(
     *         ref=@Model(type=Style::class, groups={"style"})
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Creation error"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Add Style",
     *     @Model(type=Style::class, groups={"style"})
     * )
     * @SWG\Tag(name="styles")
     *
     * @Rest\View(serializerGroups={"style"})
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function postStyle(Request $request)
    {
        $form = $this->createForm(StyleType::class);
        $form->submit($request->request->all());
        if ($form->isValid()) {
            $style = $this->apiManager->save($form->getData());
            return $this->view($style, Response::HTTP_CREATED);
        } else {
            throw new FormValidationException($form);
        }
    }

    /**
     * @Rest\Put("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=204,
     *     description="Style is updated"
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Update error"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Update a Style",
     *     @Model(type=Style::class, groups={"style"})
     * )
     * @SWG\Tag(name="styles")
     *
     * @Rest\View()
     *
     * @param Request $request
     * @param Style $style
     *
     * @return Response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateStyle(Request $request, Style $style)
    {
        $form = $this->createForm(StyleType::class, $style);
        $form->submit($request->request->all(), false);

        if ($form->isValid()) {
            $this->apiManager->save($style);
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
     *     description="Style is removed"
     *     )
     * )
     * @SWG\Tag(name="styles")
     *
     * @Rest\View()
     *
     * @param Style $style
     *
     * @return Response
     */
    public function removeStyle(Style $style)
    {
        $this->apiManager->delete($style);
        return $this->view(null, Response::HTTP_NO_CONTENT);
    }
}