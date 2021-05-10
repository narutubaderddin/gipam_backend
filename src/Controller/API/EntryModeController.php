<?php


namespace App\Controller\API;


use App\Entity\EntryMode;
use App\Exception\FormValidationException;
use App\Form\EntryModeType;
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
use FOS\RestBundle\View\View;

/**
 * Class FieldController
 * @package App\Controller\API
 * @Route("/entryModes")
 */
class EntryModeController extends  AbstractFOSRestController
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
     *     description="Returns Entry mode by id",
     *     @SWG\Schema(
     *         ref=@Model(type=EntryMode::class, groups={"entrymode", "id"})
     *     )
     * )
     * @SWG\Tag(name="EntryModes")
     * @Rest\View(serializerGroups={"entrymode", "id"})
     *
     * @param EntryMode $entryMode
     *
     * @return View
     */
    public function showEntryMode(EntryMode $entryMode)
    {
        return $this->view($entryMode, Response::HTTP_OK);
    }
    /**
     * @Rest\Get("/")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of entry modes",
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
     * @SWG\Tag(name="EntryModes")
     *
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="page number.")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="0", description="page size.")
     * @Rest\QueryParam(name="sort_by", nullable=true, default="id", description="order by")
     * @Rest\QueryParam(name="sort", requirements="(asc|desc)", nullable=true, default="asc", description="tri order asc|desc")
     * @Rest\QueryParam(name="label", map=true, nullable=false, description="filter by label. example: label[eq]=value")
     * @Rest\QueryParam(name="active" ,map=true, nullable=false, description="filter by active. example: active[eq]=1")
     * @Rest\QueryParam(name="search", map=false, nullable=true, description="search. example: search=text")
     * @Rest\View()
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return View
     */
    public function ListEntryMode(ParamFetcherInterface $paramFetcher)
    {
        $records = $this->apiManager->findRecordsByEntityName(EntryMode::class, $paramFetcher);
        return $this->view($records, Response::HTTP_OK);
    }
    /**
     * @Rest\Post("/")
     *
     * @SWG\Response(
     *     response=201,
     *     description="Returns created Entry Mode",
     *     @SWG\Schema(
     *         ref=@Model(type=EntryMode::class, groups={"entrymode", "id"})
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Creation error"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Add Entry Mode",
     *     @Model(type=EntryMode::class, groups={"entrymode"})
     * )
     * @SWG\Tag(name="EntryModes")
     *
     * @Rest\View(serializerGroups={"entrymode", "id"})
     *
     * @param Request $request
     * @return View
     */
    public function postEntryMode(Request $request)
    {
        $form = $this->createForm(EntryModeType::class);
        $form->submit($request->request->all());
        if ($form->isValid()) {
            $entrymode = $this->apiManager->save($form->getData());
            return $this->view($entrymode, Response::HTTP_CREATED);
        }
        throw new FormValidationException($form);
    }
    /**
     * @Rest\Put("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=204,
     *     description="Entry Mode is updated"
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Update error"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Entry Mode not found"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Update an Entry Mode",
     *     @Model(type=EntryMode::class, groups={"entrymode"})
     * )
     * @SWG\Tag(name="EntryModes")
     *
     * @Rest\View()
     *
     * @param Request $request
     * @param EntryMode $entrymode
     * @return View
     */
    public function updateEntryMode(Request $request, EntryMode $entrymode)
    {
        $form = $this->createForm(EntryModeType::class, $entrymode);
        $form->submit($request->request->all(), false);
        if ($form->isValid()) {
            $this->apiManager->save($entrymode);
            return $this->view(null, Response::HTTP_NO_CONTENT);
        }
        throw new FormValidationException($form);
    }
    /**
     * @Rest\Delete("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=204,
     *     description="Entry Mode is removed"
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Deleting errors"
     *     )
     * )
     * @SWG\Tag(name="EntryModes")
     *
     * @Rest\View()
     *
     * @param EntryMode $entryMode
     *
     * @return View
     */
    public function removeEntryMode(EntryMode $entryMode)
    {
        $this->apiManager->delete($entryMode);
        return $this->view(null, Response::HTTP_NO_CONTENT);
    }

}