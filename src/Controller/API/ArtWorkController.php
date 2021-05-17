<?php


namespace App\Controller\API;


use App\Entity\ArtWork;
use App\Model\ApiResponse;
use App\Repository\ArtWorkRepository;
use App\Repository\FurnitureRepository;
use App\Services\ApiManager;
use App\Services\ArtWorkService;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View;
use Spipu\Html2Pdf\Html2Pdf;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ArtWorkController
 * @package App\Controller\API
 * @Rest\Route("/artWorks")
 */
class ArtWorkController extends AbstractFOSRestController
{
    /**
     * @var ApiManager
     */
    protected $apiManager;
    /**
     * @var ValidatorInterface
     */
    protected $validator;
    /**
     * @var ArtWorkService
     */
    protected $artWorkService;

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * ArtWorkController constructor.
     * @param ApiManager $apiManager
     * @param ValidatorInterface $validator
     * @param ArtWorkService $artWorkService
     * @param EntityManagerInterface $em
     */
    public function __construct(
        ApiManager $apiManager,
        ValidatorInterface $validator,
        ArtWorkService $artWorkService,
        EntityManagerInterface $em
    )
    {
        $this->apiManager = $apiManager;
        $this->validator = $validator;
        $this->artWorkService = $artWorkService;
        $this->em = $em;
    }

    /**
     * @Rest\Get("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns Art work by id",
     *     @SWG\Schema(
     *         ref=@Model(type=ArtWork::class, groups={"location_type", "id"})
     *     )
     * )
     * @SWG\Tag(name="ArtWorks")
     * @Rest\View(serializerGroups={"art_work_list","hyperLink_furniture", "id", "art_work_details"},serializerEnableMaxDepthChecks=true)
     *
     * @param ArtWork $artWork
     * @return View
     */
    public function showArtWork(ArtWork $artWork){
        return $this->view($artWork, Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of Art Works",
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
     *     name="name",
     *     in="query",
     *     type="string",
     *     description="The field used to filter by name"
     * )
     * @SWG\Tag(name="ArtWorks")
     *
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="page number.")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="20", description="page size.")
     * @Rest\QueryParam(name="sort_by", nullable=true, default="id", description="order by")
     * @Rest\QueryParam(name="sort", requirements="(asc|desc)", nullable=true, default="asc", description="tri order asc|desc")
     * @Rest\QueryParam(name="search", nullable=true, default="", description="search")
     * @Rest\QueryParam(name="globalSearch", nullable=true, default="", description="Golbal search")
     * @Rest\QueryParam(
     *     name="sort", requirements="(asc|desc)",
     *      nullable=true, default="asc",
     *      description="sorting order asc|desc"
     * )
     * @Rest\View( serializerGroups={"art_work_list","style_furniture","materialTechnique_furniture","status_furniture",
     *     "response","era_furniture","denomination_furniture","field_furniture","mouvement_furniture",
     *     "furniture_author","id"},serializerEnableMaxDepthChecks=true)
     *
     * @param Request $request
     * @param ParamFetcherInterface $paramFetcher
     * @param ArtWorkService $artWorkService
     * @param FurnitureRepository $furnitureRepository
     * @return View
     */
    public function listArtWorks(Request $request,ParamFetcherInterface $paramFetcher, ArtWorkService $artWorkService , FurnitureRepository $furnitureRepository){
         $records=$artWorkService->findArtWorkRecord($paramFetcher,$request->get('filter',[])
             ,$request->get('advancedFilter',[]),$request->get('headerFilters',[]));
        return $this->view($records,Response::HTTP_OK);
    }

    /**
     * @param ParamFetcherInterface $paramFetcher
     * @param ArtWorkService $artWorkService
     * @Rest\Get("/autocompleteData")
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of Art Works",
     *     @SWG\Schema(
     *         @SWG\Items(ref=@Model(type=ApiResponse::class))
     *     )
     * )
     * @Rest\QueryParam(name="query", nullable=true, default="", description="query to search")
     * @Rest\QueryParam(name="type",nullable=true,default="title",description="title|description")
     * @Rest\View()
     * @return array
     */
    public function findDescriptionAutocompleteData(ParamFetcherInterface $paramFetcher,ArtWorkService $artWorkService){
        return $artWorkService->findAutocompleteData($paramFetcher->get('query')??"", $paramFetcher->get('type')??'title');
    }



    /**
     * @Rest\Get("/search")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of an ArtWork",
     *     @SWG\Schema(
     *         @SWG\Items(ref=@Model(type=ApiResponse::class))
     *     )
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
     * @SWG\Tag(name="art_works")
     *
     * @Rest\QueryParam(name="sort_by", nullable=true, default="id", description="order by")
     * @Rest\QueryParam(name="sort", requirements="(asc|desc)", nullable=true, default="asc", description="tri order asc|desc")
     * @Rest\QueryParam(name="title", nullable=true, description="filter by title. example: title[eq]=text")
     * @Rest\QueryParam(name="width", nullable=true, description="filter by width. example: width[eq]=text")
     * @Rest\QueryParam(name="height", nullable=true, description="filter by height. example: height[eq]=text")
     * @Rest\QueryParam(name="depth", nullable=true, description="filter by depth. example: depth[eq]=text")
     * @Rest\QueryParam(name="diameter", nullable=true, description="filter by diameter. example: diameter[eq]=text")
     * @Rest\QueryParam(name="weight", nullable=true, description="filter by weight. example: weight[eq]=text")
     * @Rest\QueryParam(name="stopNumber", nullable=true, description="filter by stopNumber. example: weight[eq]=text")
     * @Rest\QueryParam(name="numberOfUnit", nullable=true, description="filter by number Of Unit. weight[eq]=1")
     * @Rest\QueryParam(name="description", nullable=false, description="filter by description. example: description[eq]=text")
     * @Rest\QueryParam(name="creationDate", map=true, nullable=false, description="filter by creationDate. example: creationDate[lt]=value")
     * @Rest\QueryParam(name="depositDate", map=true, nullable=false, description="filter by depositDate. example: depositDate[lt]=value")
     * @Rest\QueryParam(name="insuranceValueDate", map=true, nullable=false, description="filter by insuranceValueDate. example: insuranceValueDate[lt]=value")
     * @Rest\QueryParam(name="totalLength", nullable=false, description="filter by totalLength. example: totalLength[eq]=1")
     * @Rest\QueryParam(name="totalLength", nullable=false, description="filter by totalLength. example: totalLength[eq]=1")
     * @Rest\QueryParam(name="totalLength", nullable=false, description="filter by totalLength. example: totalLength[eq]=1")
     * @Rest\QueryParam(name="totalWidth", nullable=false, description="filter by totalWidth. example: totalWidth[eq]=1")
     * @Rest\QueryParam(name="totalHeight", nullable=false, description="filter by totalHeight. example: totalHeight[eq]=1")
     * @Rest\QueryParam(name="registrationSignature", nullable=false, description="filter by registrationSignature. example: registrationSignature[eq]=text")
     * @Rest\QueryParam(name="descriptiveWords", nullable=false, description="filter by descriptiveWords. example: descriptiveWords[eq]=text")
     * @Rest\QueryParam(name="insuranceValue", nullable=false, description="filter by insuranceValue. example: insuranceValue[eq]=1")
     * @Rest\QueryParam(name="denomination", map=true, nullable=true, description="filter by denomination. example: denomination[eq]=value")
     * @Rest\QueryParam(name="field", map=true, nullable=true, description="filter by field. example: field[eq]=value")
     * @Rest\QueryParam(name="searchArt", nullable=false, description="filter by search. example: search[eq]=1")
     * @Rest\QueryParam(name="mode", nullable=false, description="filter by mode. example: mode[eq]=1")
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="page number.")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="20", description="page size.")
     * @Rest\QueryParam(name="sort_by", nullable=true, default="id", description="order by")
     * @Rest\QueryParam(name="sort", requirements="(asc|desc)", nullable=true, default="asc", description="tri order asc|desc")
     * @Rest\View(serializerGroups={"response","art_work_list"})
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return View
     */
    public function searchArtWorks(ParamFetcherInterface $paramFetcher)
    {
        $records = $this->apiManager->searchByEntityName(ArtWork::class, $paramFetcher);
        return $this->view($records, Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/exportListArtWorks")
     *
     * @SWG\Response(
     *     response=201,
     *     description="Returns created Field",
     *     @SWG\Schema(
     *         ref=@Model(type=Field::class, groups={"field"})
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Creation error"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Add Request",
     *     @Model(type=Request::class, groups={"request"})
     * )
     * @SWG\Tag(name="ArtWorks")
     *
     * @Rest\QueryParam(name="title", nullable=true, description="filter by title. example: title[eq]=text")
     * @Rest\QueryParam(name="width", nullable=true, description="filter by width. example: width[eq]=text")
     * @Rest\QueryParam(name="height", nullable=true, description="filter by height. example: height[eq]=text")
     * @Rest\QueryParam(name="depth", nullable=true, description="filter by depth. example: depth[eq]=text")
     * @Rest\QueryParam(name="diameter", nullable=true, description="filter by diameter. example: diameter[eq]=text")
     * @Rest\QueryParam(name="weight", nullable=true, description="filter by weight. example: weight[eq]=text")
     * @Rest\QueryParam(name="stopNumber", nullable=true, description="filter by stopNumber. example: weight[eq]=text")
     * @Rest\QueryParam(name="numberOfUnit", nullable=true, description="filter by number Of Unit. weight[eq]=1")
     * @Rest\QueryParam(name="description", nullable=false, description="filter by description. example: description[eq]=text")
     * @Rest\QueryParam(name="creationDate", map=true, nullable=false, description="filter by creationDate. example: creationDate[lt]=value")
     * @Rest\QueryParam(name="depositDate", map=true, nullable=false, description="filter by depositDate. example: depositDate[lt]=value")
     * @Rest\QueryParam(name="insuranceValueDate", map=true, nullable=false, description="filter by insuranceValueDate. example: insuranceValueDate[lt]=value")
     * @Rest\QueryParam(name="totalLength", nullable=false, description="filter by totalLength. example: totalLength[eq]=1")
     * @Rest\QueryParam(name="totalLength", nullable=false, description="filter by totalLength. example: totalLength[eq]=1")
     * @Rest\QueryParam(name="totalLength", nullable=false, description="filter by totalLength. example: totalLength[eq]=1")
     * @Rest\QueryParam(name="totalWidth", nullable=false, description="filter by totalWidth. example: totalWidth[eq]=1")
     * @Rest\QueryParam(name="totalHeight", nullable=false, description="filter by totalHeight. example: totalHeight[eq]=1")
     * @Rest\QueryParam(name="registrationSignature", nullable=false, description="filter by registrationSignature. example: registrationSignature[eq]=text")
     * @Rest\QueryParam(name="descriptiveWords", nullable=false, description="filter by descriptiveWords. example: descriptiveWords[eq]=text")
     * @Rest\QueryParam(name="insuranceValue", nullable=false, description="filter by insuranceValue. example: insuranceValue[eq]=1")
     * @Rest\QueryParam(name="denomination", map=true, nullable=true, description="filter by denomination. example: denomination[eq]=value")
     * @Rest\QueryParam(name="field", map=true, nullable=true, description="filter by field. example: field[eq]=value")
     * @Rest\QueryParam(name="searchArt", nullable=false, description="filter by search. example: search[eq]=1")
     * @Rest\QueryParam(name="mode", nullable=false, description="filter by mode. example: mode[eq]=1")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="20", description="page size.")
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="page number.")
     * @Rest\QueryParam(name="sort_by", nullable=true, default="id", description="order by")
     * @Rest\QueryParam(name="sort", requirements="(asc|desc)", nullable=true, default="asc", description="tri order asc|desc")
     * @Rest\View(serializerGroups={"request_details"})
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function exportListArtWorks(ParamFetcherInterface $paramFetcher)
    {
        $records = $this->apiManager->searchByEntityName(ArtWork::class, $paramFetcher);
        $artWorks = $records->getResults();
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html = $this->renderView('artWorks/list-pdf.html.twig', array(
            'artWorks'  => $artWorks
        ));
        $html2pdf->writeHTML($html);
        $path =  $this->getParameter('kernel.project_dir').DIRECTORY_SEPARATOR.'var' .DIRECTORY_SEPARATOR . 'file_xxxx.pdf';
        $html2pdf->Output($path, 'F');
        return $this->file($path,'Oeuvres_Graphiques.pdf')->deleteFileAfterSend();

    }
}