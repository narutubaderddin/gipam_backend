<?php

namespace App\Controller\API;

use App\Entity\AttachmentType;
use App\Exception\FormValidationException;
use App\Form\AttachmentTypeType;
use App\Model\ApiResponse;
use App\Services\ApiManager;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class AttachmentTypeController
 * @package App\Controller\API
 * @Route("/attachmentTypes")
 */
class AttachmentTypeController extends AbstractFOSRestController
{
    /**
     * @var ApiManager
     */
    protected $apiManager;

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    public function __construct(
        ApiManager $apiManager,
        ValidatorInterface $validator
    )
    {
        $this->apiManager = $apiManager;
        $this->validator = $validator;
    }

    /**
     * @Rest\Get("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns AttachmentType by id",
     *     @SWG\Schema(
     *         ref=@Model(type=AttachmentType::class, groups={"attachment_type", "id"})
     *     )
     * )
     * @SWG\Tag(name="attachmentTypes")
     * @Rest\View(serializerGroups={"attachment_type", "id"})
     *
     * @param AttachmentType $attachmentType
     *
     * @return View
     */
    public function showAttachmentType(AttachmentType $attachmentType)
    {
        return $this->view($attachmentType, Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of Attachment Types",
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
     * @SWG\Parameter(
     *     name="label",
     *     in="query",
     *     type="string",
     *     description="The field used to filter by label"
     * )
     * @SWG\Tag(name="attachmentTypes")
     *
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="page number.")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="0", description="page size.")
     * @Rest\QueryParam(name="sort_by", nullable=true, default="id", description="order by")
     * @Rest\QueryParam(name="search", map=false, nullable=true, description="search. example: search=text")
     * @Rest\QueryParam(
     *     name="sort", requirements="(asc|desc)",
     *      nullable=true, default="asc",
     *      description="sorting order asc|desc"
     * )
     * @Rest\QueryParam(name="label",map=true, nullable=false, description="filter by label. example: label[eq]=value")
     * @Rest\QueryParam(name="search", map=false, nullable=true, description="search. example: search=text")
     *
     * @Rest\View()
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return View
     */
    public function listAttachmentTypes(ParamFetcherInterface $paramFetcher)
    {
        $records = $this->apiManager->findRecordsByEntityName(AttachmentType::class, $paramFetcher);
        return $this->view($records, Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/")
     *
     * @SWG\Response(
     *     response=201,
     *     description="Returns created Attachment Type",
     *     @SWG\Schema(
     *         ref=@Model(type=AttachmentType::class, groups={"attachment_type"})
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Creation error"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Add AttachmentType",
     *     @Model(type=AttachmentType::class, groups={"attachment_type"})
     * )
     * @SWG\Tag(name="attachmentTypes")
     *
     * @Rest\View(serializerGroups={"attachment_type", "id"})
     *
     * @param Request $request
     * @return View
     */
    public function postAttachmentType(Request $request)
    {
        $form = $this->createForm(AttachmentTypeType::class);
        $form->submit($request->request->all());
        if ($form->isValid()) {
            $attachmentType = $this->apiManager->save($form->getData());
            return $this->view($attachmentType, Response::HTTP_CREATED);
        }
        throw new FormValidationException($form);
    }

    /**
     * @Rest\Put("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=204,
     *     description="Attachment Type is updated"
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Update error"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Attachment Type not found"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Update an Attachment Type",
     *     @Model(type=AttachmentType::class, groups={"attachment_type"})
     * )
     * @SWG\Tag(name="attachmentTypes")
     *
     * @Rest\View()
     *
     * @param Request $request
     * @param AttachmentType $attachmentType
     * @return View
     */
    public function updateAttachmentType(Request $request, AttachmentType $attachmentType)
    {
        $form = $this->createForm(AttachmentTypeType::class, $attachmentType);
        $form->submit($request->request->all(), false);
        if ($form->isValid()) {
            $this->apiManager->save($attachmentType);
            return $this->view(null, Response::HTTP_NO_CONTENT);
        }
        throw new FormValidationException($form);
    }

    /**
     * @Rest\Delete("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=204,
     *     description="Attachment Type is removed"
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Deleting errors"
     *     )
     * )
     * @SWG\Tag(name="attachmentTypes")
     *
     * @Rest\View()
     *
     * @param AttachmentType $attachmentType
     *
     * @return View
     */
    public function removeAttachmentType(AttachmentType $attachmentType)
    {
        $this->apiManager->delete($attachmentType);
        return $this->view(null, Response::HTTP_NO_CONTENT);
    }
}
