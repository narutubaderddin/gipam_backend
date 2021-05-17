<?php


namespace App\Controller\API;

use App\Entity\Hyperlink;
use App\Exception\FormValidationException;
use App\Form\HyperlinkType;
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
 * Class HyperlinkController
 * @package App\Controller\API
 * @Route("/hyperLink")
 */
class HyperlinkController extends AbstractFOSRestController
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
     *         ref=@Model(type=Hyperlink::class, groups={"artwork", "id"})
     *     )
     * )
     * @SWG\Tag(name="hyperLink")
     * @Rest\View(serializerGroups={"artwork", "id"})
     *
     * @param Hyperlink $hyperlink
     * @return View
     */
    public function showPhotographyType(Hyperlink $hyperlink)
    {
        return $this->view($hyperlink, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return View
     * @Rest\Post("")
     * @SWG\Response(
     *     response=201,
     *     description="Returns created Hyper Link",
     *     @SWG\Schema(
     *         ref=@Model(type=Hyperlink::class, groups={"artwork", "id"})
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="creation error"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *      @Model(type=Hyperlink::class, groups={""}),
     *     description="Add HyperLink")
     * @SWG\Tag(name="hyperLink")
     * @Rest\View(serializerGroups={"artwork", "id"})
     */
    public function postHyperLink(Request $request)
    {
        $form = $this->createForm(HyperlinkType::class);
        $data = $this->apiManager->getPostDataFromRequest($request);
        $form->submit($data);
        if ($form->isValid()) {
            $hyperLink = $this->apiManager->save($form->getData());
            return $this->view($hyperLink, Response::HTTP_CREATED);
        }
        throw new FormValidationException($form);
    }

    /**
     * @param Hyperlink $hyperlink
     * @param Request $request
     * @Rest\Patch("/{id}",requirements={"id"="\d+"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns updated Hyper Link",
     *     @SWG\Schema(
     *         ref=@Model(type=Hyperlink::class, groups={"artwork", "id"})
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="update error"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     @Model(type=Hyperlink::class, groups={""}),
     *     description="update HyperLink")
     * @SWG\Tag(name="hyperLink")
     * @Rest\View(serializerGroups={"artwork", "id"})
     *
     * @return View
     */
    public function updateHyperLink(Hyperlink $hyperlink, Request $request)
    {
        $form = $this->createForm(HyperlinkType::class,$hyperlink);
        $data = $this->apiManager->getPostDataFromRequest($request);
        $form->submit($data);
        if($form->isValid()){
            $hyperLink = $this->apiManager->save($form->getData());
            return $this->view($hyperLink, Response::HTTP_OK);
        }
        throw new FormValidationException($form);
    }

}