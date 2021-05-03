<?php


namespace App\Services;


use App\Entity\MaterialTechnique;
use App\Model\ApiResponse;
use App\Repository\MaterialTechniqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use FOS\RestBundle\Request\ParamFetcherInterface;

class MaterialTechniqueService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager =$entityManager;
    }

    /**
     * @param ParamFetcherInterface $paramFetcher
     * @return ApiResponse
     * @throws NoResultException
     * @throws NonUniqueResultException
     */

    public function findMaterialTechniqueByFieldAndDenomination(ParamFetcherInterface $paramFetcher){
        $page = $paramFetcher->get('page', true)?? 1;
        $limit = $paramFetcher->get('limit', true)?? 5;
        $field= $paramFetcher->get('field')?? "";
        $denomination=$paramFetcher->get('denomination')?? "";
        /**
         * @var MaterialTechniqueRepository $materialTechniqueRepo
         */
        $materialTechniqueRepo = $this->entityManager->getRepository(MaterialTechnique::class);
        $recordsCount =(int) $materialTechniqueRepo->findByFieldAndDenomination($page,$limit,$field,$denomination,true);
        $records = $materialTechniqueRepo->findByFieldAndDenomination($page,$limit,$field,$denomination);
        return new ApiResponse(
          $page,
          $limit,
          $recordsCount,
          $materialTechniqueRepo->count([]),
          $records

        );
    }
}