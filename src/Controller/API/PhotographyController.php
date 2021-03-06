<?php


namespace App\Controller\API;


use App\Entity\ArtWork;
use App\Entity\Furniture;
use App\Exception\FormValidationException;
use App\Form\PhotographyType;
use App\Services\ApiManager;
use App\Services\FileUploader;
use App\Services\PhotographyService;
use App\Services\ArtWorkService;
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
    /**
     * @var ArtWorkService
     */
    private $artWorkService;


    public function __construct(
        ApiManager $apiManager,
        ArtWorkService $artWorkService
    )
    {
        $this->apiManager = $apiManager;
        $this->artWorkService = $artWorkService;

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
            $furniture= $form->getData()->getFurniture();

            $response=$this->artWorkService->checkPrincipalPhoto($furniture, $form->getData(), $form->getData()->getPhotographyType()->getType());
            if(is_array($response)){
                return $this->view($response, Response::HTTP_BAD_REQUEST);
            }else {
                $photography = $this->apiManager->save($form->getData());
                return $this->view($photography, Response::HTTP_CREATED);
            }

        }
        throw new FormValidationException($form);

    }

    /**
     * @param Photography $photography
     * @param Request $request
     * @param PhotographyService $photographyService
     * @return View
     * @throws \Exception
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
     */
    public function updatePhotography(Photography $photography, Request $request,PhotographyService $photographyService)
    {
        $form = $this->createForm(PhotographyType::class, $photography);
        $data = $this->apiManager->getPostDataFromRequest($request);dd($data);
        $data = $photographyService->formatUpdatePhotographyData($data,$photography);
        $form->submit($data,false);
        if ($form->isValid()) {
            /**
             * @var Photography $photography
             * @var ArtWork $furniture
             */
            $furniture= $photography->getFurniture();

            $response=$this->artWorkService->checkPrincipalPhoto($furniture, $photography, $form->getData()->getPhotographyType()->getType());
            if(is_array($response)){
                return $this->view($response, Response::HTTP_BAD_REQUEST);
            }else {
                $photography = $this->apiManager->save($form->getData());
                return $this->view($photography, Response::HTTP_OK);
            }
        }
        throw new FormValidationException($form);
    }

    /**
     * @param Photography $photography
     * @Rest\Delete("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=204,
     *     description="Photographie is removed"
     *     )
     * )
     * @SWG\Tag(name="photography")
     * @Rest\View()
     * @return View
     */
    public function removePhotographie(Photography $photography){
        $furniture = $photography->getFurniture();
        $type=$photography->getPhotographyType()->getType();

        $this->artWorkService->checkPrincipalPhoto($furniture, $photography, $type);

        $furniture->removePhotography($photography);
        $this->apiManager->delete($photography);
        return $this->view(null,Response::HTTP_NO_CONTENT);
    }


}