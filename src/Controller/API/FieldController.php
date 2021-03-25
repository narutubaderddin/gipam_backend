<?php


namespace App\Controller\API;


use App\Entity\Domaine;
use App\Form\FieldType;
use App\Repository\DomaineRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class FieldController
 * @package App\Controller\API
 * @Route("/field")
 */
class FieldController extends AbstractFOSRestController
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager

    )
    {
        $this->entityManager = $entityManager;
    }


    /**
     * @Rest\Post("/add",name="add_field_action")
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return Domaine|View
     * @Rest\View(statusCode=201)
     */
    public function addFieldAction(Request $request, ValidatorInterface $validator)
    {
        $field = new Domaine();
        $form = $this->createForm(FieldType::class, $field);
        $form->submit($request->request->all(), false);
        if (count($validatorErrors = $validator->validate($form)) > 0) {
            return View::create($validatorErrors, Response::HTTP_BAD_REQUEST);
        }
        $this->entityManager->persist($field);
//            $this->entityManager->flush();
        return $field;

    }

    /**
     * @Rest\Get("/list",name="list_Fields")
     * @Rest\View(serializerGroups={"default"})
     * @param DomaineRepository $fieldRepository
     * @return Domaine[]
     */
    public function listFieldsAction(DomaineRepository $fieldRepository)
    {

        return $fieldRepository->findAll();

    }

    public function updateFieldAction()
    {

    }

}