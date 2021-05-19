<?php


namespace App\Controller\API;


use App\Entity\Attachment;
use App\Exception\FormValidationException;
use App\Form\AttachmentType;
use App\Services\ApiManager;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Swagger\Annotations as SWG;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\Model;

/**
 * Class AttachmentController
 * @package App\Controller\API
 * @Rest\Route("/attachments")
 */
class AttachmentController extends  AbstractFOSRestController
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
     *     description="Returns Photography Type by id",
     *     @SWG\Schema(
     *         ref=@Model(type=Attachment::class, groups={"artwork", "id"})
     *     )
     * )
     * @SWG\Tag(name="attachment")
     * @Rest\View(serializerGroups={"artwork", "id"})
     *
     * @param Attachment $attachement
     * @return View
     */
    public function showAttachment(Attachment $attachement){
        return  $this->view($attachement, Response::HTTP_OK);
    }
    /**
     * @param Request $request
     * @return View
     * @Rest\Post("")
     * @SWG\Response(
     *     response=201,
     *     description="Returns created attachement",
     *     @SWG\Schema(
     *         ref=@Model(type=Attachment::class, groups={"artwork", "id"})
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="creation error"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *      @Model(type=Attachment::class, groups={""}),
     *     description="Add attachement")
     * @SWG\Tag(name="attachment")
     * @Rest\View(serializerGroups={"artwork", "id"})
     */
    public function postAttachment(Request $request){
        $form = $this->createForm(AttachmentType::class);
        $data = $this->apiManager->getPostDataFromRequest($request);
        $form->submit($data,false);
        if($form->isValid()){
            $attachment = $this->apiManager->save($form->getData());
            return  $this->view($attachment,Response::HTTP_CREATED);
        }
        throw  new FormValidationException($form);
    }

    /**
     * @param Attachment $attachment
     * @param Request $request
     * @Rest\Patch("/{id}",requirements={"id"="\d+"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns updated attachement",
     *     @SWG\Schema(
     *         ref=@Model(type=Attachment::class, groups={"artwork", "id"})
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="update error"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *      @Model(type=Attachment::class, groups={""}),
     *     description="Add attachement")
     * @SWG\Tag(name="attachment")
     * @Rest\View(serializerGroups={"artwork", "id"})
     * @return View
     */
    public function updateAttachement(Attachment $attachment ,Request $request){
        $form = $this->createForm(AttachmentType::class,$attachment);
        $data = $this->apiManager->getPostDataFromRequest($request);
        $form->submit($data,false);
        if($form->isValid()){
            $attachment = $this->apiManager->save($form->getData());
            return  $this->view($attachment,Response::HTTP_CREATED);
        }
        throw new FormValidationException($form);
    }

    /**
     * @param Attachment $attachment
     * @Rest\Delete("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=204,
     *     description="Attachment is removed"
     *     )
     * )
     * @SWG\Tag(name="attachment")
     * @Rest\View()
     * @return View
     */
    public function removeAttachement(Attachment $attachment){
        $furniture = $attachment->getFurniture();
        $furniture->removeAttachment($attachment);
        $this->apiManager->delete($attachment);
        return $this->view(null,Response::HTTP_NO_CONTENT);
    }

}