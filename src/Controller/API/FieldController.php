<?php


namespace App\Controller\API;


use App\Entity\Field;
use App\Form\FieldType;
use App\Repository\FieldRepository;
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
 * Class FieldController
 * @package App\Controller\API
 * @Route("/fields")
 */
class FieldController extends AbstractFOSRestController
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
     *     description="Returns Field by id",
     *     @SWG\Schema(
     *         ref=@Model(type=Field::class)
     *     )
     * )
     * @SWG\Tag(name="fields")
     *
     * @param Field $field
     *
     * @return Response
     */
    public function showField(Field $field)
    {
        $view = $this->view($field, Response::HTTP_OK);
        return $this->handleView($view);
    }

    /**
     * @Rest\Get("/")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of an Field",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Field::class))
     *     )
     * )
     * @SWG\Parameter(
     *     name="offset",
     *     in="query",
     *     type="string",
     *     description="The field used to offset list"
     * )
     * @SWG\Parameter(
     *     name="limit",
     *     in="query",
     *     type="string",
     *     description="The field used to limit list"
     * )
     * @SWG\Tag(name="fields")
     *
     * @Rest\QueryParam(name="offset", requirements="\d+", default="0", description="offset")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="20", description="limit.")
     * @Rest\QueryParam(name="sort_by", nullable=true, default="id", description="order by")
     * @Rest\QueryParam(name="sort", requirements="(asc|desc)", nullable=true, default="asc", description="tri order asc|desc")
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return Response
     */
    public function listFields(ParamFetcherInterface $paramFetcher)
    {
        $records = $this->apiManager->findRecordsByEntityName(Field::class, $paramFetcher);
        $view = $this->view($records, Response::HTTP_OK);
        return $this->handleView($view);
    }

    /**
     * @Rest\Post("/")
     *
     * @SWG\Response(
     *     response=201,
     *     description="Returns created Field",
     *     @SWG\Schema(
     *         ref=@Model(type=Field::class)
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Creation error"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Add Field",
     *     @Model(type=Field::class, groups={"field"})
     * )
     * @SWG\Tag(name="fields")
     *
     * @param Request $request
     * @param FieldRepository $repository
     *
     * @return Response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function postField(Request $request, FieldRepository $repository)
    {
        $form = $this->createForm(FieldType::class);
        $form->submit($request->request->all());
        if ($form->isValid()) {
            $field = $this->apiManager->save($form->getData());
            $view = $this->view($field, Response::HTTP_CREATED);
            return $this->handleView($view);
        } else {
            $view = $this->view($form, Response::HTTP_BAD_REQUEST);
            return $this->handleView($view);
        }
    }

    /**
     * @Rest\Put("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=204,
     *     description="Field is updated"
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Update error"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Add Field",
     *     @Model(type=Field::class, groups={"field"})
     * )
     * @SWG\Tag(name="fields")
     *
     * @param Request $request
     * @param Field $field
     * @param FieldRepository $repository
     *
     * @return Response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateField(Request $request, Field $field, FieldRepository $repository)
    {
        $form = $this->createForm(FieldType::class, $field);
        $form->submit($request->request->all(), false);

        if ($form->isValid()) {
            $this->apiManager->save($field);
            $view = $this->view(null, Response::HTTP_NO_CONTENT);
            return $this->handleView($view);
        } else {
            $view = $this->view($form, Response::HTTP_BAD_REQUEST);
            return $this->handleView($view);
        }
    }

    /**
     * @Rest\Delete("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=204,
     *     description="Field is removed"
     *     )
     * )
     * @SWG\Tag(name="fields")
     *
     * @param Field $field
     *
     * @return Response
     */
    public function removeField(Field $field)
    {
        $this->apiManager->delete($field);
        $view = $this->view(null, Response::HTTP_NO_CONTENT);
        return $this->handleView($view);
    }
}