<?php


namespace App\Controller\API;


use App\Entity\ArtWork;
use App\Entity\Furniture;
use App\Exception\FormValidationException;
use App\Form\PhotographyType;
use App\Services\ApiManager;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Photography;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use function React\Promise\all;

/**
 * Class PhotographyController
 * @package App\Controller\API
 * @Rest\Route("/photography")
 */
class PhotographyController extends AbstractFOSRestController
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
     * @param Photography $photography
     * @return View
     * @Rest\Get("/{id}",requirements={"id"="\d+"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns Photography  by id",
     *     @SWG\Schema(
     *         ref=@Model(type=Photography::class, groups={"photography", "id"})
     *     )
     * )
     * @SWG\Tag(name="photography")
     * @Rest\View(serializerGroups={"photography", "id"})
     */
    public function showPhotography(Photography $photography)
    {
        return $this->view($photography, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return View
     * @Rest\Post("")
     * @SWG\Response(
     *     response=201,
     *     description="Returns created Photography",
     *     @SWG\Schema(
     *         ref=@Model(type=Photography::class, groups={"photography"})
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="creation error"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *      @Model(type=Photography::class, groups={"photography"}),
     *     description="Add photography")
     * @SWG\Tag(name="photography")
     * @Rest\View(serializerGroups={})
     * )
     */
    public function postPhotography(Request $request)
    {
        $form = $this->createForm(PhotographyType::class);
        $data = $this->apiManager->getPostDataFromRequest($request);
        $form->submit($data);
        if ($form->isValid()) {
            /**
             * @var Photography $photography
             */
            $photography = $this->apiManager->save($form->getData());
            return $this->view($photography, Response::HTTP_CREATED);
        }
        throw new FormValidationException($form);

    }

    /**
     * @param Photography $photography
     * @param Request $request
     * @Rest\Patch("/{id}",requirements={"id"="\d+"})
     * To Send File in Request URL must be spoofed
     *Send Post Request and add  ?_method=PATCH to the URL
     * @SWG\Response(
     *     response=200,
     *     description="Returns updated Photography",
     *     @SWG\Schema(
     *         ref=@Model(type=Photography::class, groups={"photography"})
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="update error"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="update photography",
     *       @Model(type=Photography::class, groups={"photography"})
     * )
     * @SWG\Tag(name="photography")
     * @Rest\View(serializerGroups={})
     *
     * @return View
     */
    public function updatePhotography(Photography $photography, Request $request)
    {
        $form = $this->createForm(PhotographyType::class, $photography);
        $data = $this->apiManager->getPostDataFromRequest($request);
        $form->submit($data,false);
        if ($form->isValid()) {
            /**
             * @var Photography $photography
             */
            $photography = $this->apiManager->save($form->getData());
            return $this->view($photography, Response::HTTP_OK);
        }
        throw new FormValidationException($form);
    }


}