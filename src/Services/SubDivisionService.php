<?php


namespace App\Services;


use App\Entity\SubDivision;
use App\Model\ApiResponse;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Request\ParamFetcherInterface;

class SubDivisionService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager =$entityManager;
    }

    public function findSubdivisionByCriteria(ParamFetcherInterface $paramFetcher){
        $page = $paramFetcher->get('page', true)?? 1;
        $limit = $paramFetcher->get('limit', true)?? 5;
        $ministry = $paramFetcher->get('ministry', true)?? "";
        $establishment = $paramFetcher->get('establishment', true)?? "";
        $subDivisionRepository = $this->entityManager->getRepository(SubDivision::class);
        $recordsCount  =(int)  $subDivisionRepository->findSubDivisionByCriteria($page,$limit,$ministry,$establishment,true);
        $records = $subDivisionRepository->findSubDivisionByCriteria($page,$limit,$ministry,$establishment);
        return new ApiResponse(
            $page,$limit,
            $recordsCount,$subDivisionRepository->count([]),
            $records
        );

    }
}