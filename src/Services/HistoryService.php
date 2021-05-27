<?php


namespace App\Services;


use App\Model\ApiResponse;
use App\Talan\AuditBundle\Exception\AuditException;
use FOS\RestBundle\Request\ParamFetcherInterface;

class HistoryService
{

    /**
     * @var AuditReader
     */
    private $auditReader;

    public function __construct(AuditReader $auditReader)
    {
        $this->auditReader = $auditReader;
    }

    /**
     * @param ParamFetcherInterface $paramFetcher
     * @param $class
     * @param $id
     * @return ApiResponse
     * @throws AuditException
     */
    public function getEntityHitoryRecords(ParamFetcherInterface $paramFetcher, $class, $id)
    {
        $page = $paramFetcher->get('page', true) ?? 1;
        $limit = $paramFetcher->get('limit', true) ?? 20;
        $search = $paramFetcher->get('search', true) ?? null;
        $historyRecords =$this->formatHistoryRecords($this->auditReader->getEntityHistory($class, $id, $page, $limit,$search, true));
        $historyFiltredCount =$this->auditReader->getEntityHistoryCount($class, $id,$search);
        $historyCount = $this->auditReader->getEntityHistoryCount($class, $id);
        return new ApiResponse($page, $limit, $historyFiltredCount, $historyCount, $historyRecords);

    }

    public function formatHistoryRecords($historyRecords)
    {
        $result =[];
        foreach ( $historyRecords as $record){
            $result[]=[
                'revision'=>$record['revision'],
                'operationdate'=>(new \DateTime($record['operationdate']))->format(('Y-m-d')),
                'actor'=>$record['actor'],
                'actiontype'=>$record['actiontype']
            ];
        }
        return $result;
    }


}