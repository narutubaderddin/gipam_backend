<?php

namespace App\Controller\API;

use App\Entity\Person;
use App\Exception\FormValidationException;
use App\Form\PersonType;
use App\Model\ApiResponse;
use App\Services\ApiManager;
use FOS\RestBundle\Context\Context;
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
 * Class PersonController
 * @package App\Controller\API
 * @Route("/persons")
 */
class PersonController extends AbstractFOSRestController
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
     *     description="Returns a Person by id",
     *     @SWG\Schema(
     *         ref=@Model(type=Person::class, groups={"person", "id"})
     *     )
     * )
     * @SWG\Tag(name="persons")
     * @Rest\View(serializerGroups={"person", "id"})
     *
     * @param Person $person
     *
     * @return View
     */
    public function showPerson(Person $person)
    {
        return $this->view($person, Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of Person",
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
     *     name="lastName",
     *     in="query",
     *     type="string",
     *     description="The field used to filter by lastName"
     * )
     * @SWG\Parameter(
     *     name="firstName",
     *     in="query",
     *     type="string",
     *     description="The field used to filter by firstName"
     * )
     * @SWG\Parameter(
     *     name="website",
     *     in="query",
     *     type="string",
     *     description="The field used to filter by website"
     * )
     * @SWG\Parameter(
     *     name="phone",
     *     in="query",
     *     type="string",
     *     description="The field used to filter by phone"
     * )
     * @SWG\Parameter(
     *     name="email",
     *     in="query",
     *     type="string",
     *     description="The field used to filter by email"
     * )
     * @SWG\Tag(name="persons")
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
     * @Rest\QueryParam(name="lastName",map=true, nullable=false, description="filter by lastName. example: lastName[eq]=value")
     * @Rest\QueryParam(name="firstName",map=true, nullable=false, description="filter by firstName. example: firstName[eq]=value")
     * @Rest\QueryParam(name="website",map=true, nullable=false, description="filter by website. example: website[eq]=value")
     * @Rest\QueryParam(name="phone",map=true, nullable=false, description="filter by phone. example: phone[eq]=value")
     * @Rest\QueryParam(name="email",map=true, nullable=false, description="filter by email. example: email[eq]=value")
     * @Rest\QueryParam(name="active",map=true, nullable=false, description="filter by active. example: active[eq]=value")
     * @Rest\QueryParam(name="author", nullable=false, description="filter by author id. example: author[eq]=value")
     * @Rest\QueryParam(name="search", map=false, nullable=true, description="search. example: search=text")
     *
     * @Rest\View()
     *
     * @param ParamFetcherInterface $paramFetcher
     * @param Request $request
     * @return View
     */
    public function listPersons(ParamFetcherInterface $paramFetcher, Request $request)
    {
        $serializerGroups = $request->get('serializer_group', '["id", "person", "short"]');
        $serializerGroups = json_decode($serializerGroups, true);
        $serializerGroups[] = "response";
        $context = new Context();
        $context->setGroups($serializerGroups);
        $records = $this->apiManager->findRecordsByEntityName(Person::class, $paramFetcher);
        return $this->view($records, Response::HTTP_OK)->setContext($context);
    }

    /**
     * @Rest\Post("/")
     *
     * @SWG\Response(
     *     response=201,
     *     description="Returns created Attachment Type",
     *     @SWG\Schema(
     *         ref=@Model(type=Person::class, groups={"person"})
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Creation error"
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="Add Person",
     *     @Model(type=Person::class, groups={"person"})
     * )
     * @SWG\Tag(name="persons")
     *
     * @Rest\View(serializerGroups={"person", "id"})
     *
     * @param Request $request
     * @return View
     */
    public function postPerson(Request $request)
    {
        $form = $this->createForm(PersonType::class);
        $form->submit($request->request->all());
        if ($form->isValid()) {
            $person = $this->apiManager->save($form->getData());
            return $this->view($person, Response::HTTP_CREATED);
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
     *     @Model(type=Person::class, groups={"person"})
     * )
     * @SWG\Tag(name="persons")
     *
     * @Rest\View()
     *
     * @param Request $request
     * @param Person $person
     * @return View
     */
    public function updatePerson(Request $request, Person $person)
    {
        $form = $this->createForm(PersonType::class, $person);
        $form->submit($request->request->all(), false);
        if ($form->isValid()) {
            $this->apiManager->save($person);
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
     * @SWG\Tag(name="persons")
     *
     * @Rest\View()
     *
     * @param Person $person
     *
     * @return View
     */
    public function removePerson(Person $person)
    {
        $this->apiManager->delete($person);
        return $this->view(null, Response::HTTP_NO_CONTENT);
    }
}
