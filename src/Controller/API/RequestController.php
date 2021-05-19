<?php

namespace App\Controller\API;

use App\Entity\ArtWork;
use App\Entity\Request as Requests;
use App\Exception\FormValidationException;
use App\Form\FieldType;
use App\Form\RequestType;
use App\Model\ApiResponse;
use App\Services\ApiManager;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Spipu\Html2Pdf\Html2Pdf;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Swagger\Annotations as SWG;

/**
 * Class FieldController
 * @package App\Controller\API
 * @Route("/requests")
 */
class RequestController extends AbstractFOSRestController
{

    /**
     * @var ApiManager
     */
    protected $apiManager;

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    public function __construct(
        ApiManager $apiManager,
        EntityManagerInterface $em
    )
    {
        $this->apiManager = $apiManager;
        $this->em = $em;
    }


    /**
     * @Rest\Get("/")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of an Field",
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
     * @SWG\Tag(name="requests")
     *
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="page number.")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="0", description="page size.")
     * @Rest\QueryParam(name="sort_by", nullable=true, default="id", description="order by")
     * @Rest\QueryParam(name="sort", requirements="(asc|desc)", nullable=true, default="asc", description="tri order asc|desc")
     * @Rest\QueryParam(name="label", map=true, nullable=false, description="filter by label. example: label[eq]=value")
     * @Rest\QueryParam(name="mail" ,map=true, nullable=false, description="filter by active. example: active[eq]=1")
     * @Rest\QueryParam(name="establishement", map=true, nullable=false, description="filter by establishement.")
     * @Rest\QueryParam(name="subDivision", map=true, nullable=false, description="filter by subDivision.")
     * @Rest\QueryParam(name="building", map=true, nullable=false, description="filter by building.")
     * @Rest\QueryParam(name="requestStatus", map=true, nullable=false, description="filter by requestStatus.")
     * @Rest\QueryParam(name="level", map=true, nullable=false, description="filter by level.")
     * @Rest\QueryParam(name="firstNameApplicant", map=true, nullable=false, description="filter by firstNameApplicant.")
     * @Rest\QueryParam(name="firstNameApplicant", map=true, nullable=false, description="filter by firstNameApplicant.")
     * @Rest\QueryParam(name="lastNameApplicant", map=true, nullable=false, description="filter by lastNameApplicant.")
     * @Rest\QueryParam(name="firstName", map=true, nullable=false, description="filter by firstName.")
     * @Rest\QueryParam(name="lastName", map=true, nullable=false, description="filter by lastName.")
     * @Rest\QueryParam(name="search", map=false, nullable=true, description="search. example: search=text")
     *
     * @Rest\View(serializerGroups={"response","request_list"})
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return Response
     */
    public function listRequests(ParamFetcherInterface $paramFetcher)
    {
        $records = $this->apiManager->findRecordsByEntityName(Requests::class, $paramFetcher);
        return $this->view($records, Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/")
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
     * @SWG\Tag(name="requests")
     *
     * @Rest\View(serializerGroups={"request_details"})
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function postRequest(Request $request)
    {
        $form = $this->createForm(RequestType::class);
        $form->submit($request->request->all());
        if ($form->isValid()) {
            $field = $this->apiManager->save($form->getData());
            return $this->view($field, Response::HTTP_CREATED);
        } else {
            throw new FormValidationException($form);
        }
    }

    /**
     * @Rest\Put("/{id}", requirements={"id"="\d+"})
     *
     * @SWG\Response(
     *     response=204,
     *     description="Request is updated"
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Update error"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Update a Request",
     *     @Model(type=Field::class, groups={"request"})
     * )
     * @SWG\Tag(name="requests")
     *
     * @Rest\View()
     *
     * @param Request $request
     * @param Requests $requests
     *
     * @return Response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateField(Request $request, Requests $requests)
    {
        $params = $request->request->all();
        if($params['listArtWorks']) {
            $request->request->remove('listArtWorks');
            foreach ($params['listArtWorks'] as $item){
                $artWork = $this->em->getRepository(ArtWork::class)->find($item['id']);
                if(isset($item['requestStatus'])) {
                    $artWork->setRequestStatus($item['requestStatus']);
                }
                $this->em->persist($artWork);
            }
            $this->em->flush();
        }
        $form = $this->createForm(RequestType::class, $requests);
        $form->submit($request->request->all(), false);

        if ($form->isValid()) {
            $this->apiManager->save($requests);
            return $this->view(null, Response::HTTP_NO_CONTENT);
        } else {
            throw new FormValidationException($form);
        }
    }


    /**
     * @Rest\Get("/exportCurrentRequests")
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
     * @SWG\Tag(name="requests")
     *
     * @Rest\View(serializerGroups={"request_details"})
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function exportCurrentRequests(Request $request)
    {
        $artWorksIds = json_decode($request->get('artWorks'));
        $artWorks = [];
        foreach ($artWorksIds as $artWorkId){
            $artWorks[] = $this->em->getRepository(ArtWork::class)->find($artWorkId);
        }
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