<?php

namespace App\Command\Utils;

use App\Entity\Action;
use App\Entity\Furniture;
use App\Entity\Report;
use App\Entity\ReportSubType;

trait ReportActionTrait
{
    private function addReports($oldFurniture, Furniture &$newFurniture)
    {
        $actionMappingTable = MigrationDb::getMappingTable('action');
        $oldRelation = $oldFurniture[$actionMappingTable['rel_objet_mobilier']];
        $criteria = [$actionMappingTable['rel_objet_mobilier'] => $oldRelation];
        $oldActions = $this->migrationRepository
            ->getBy(MigrationRepository::$oldDBConnection, $actionMappingTable['table'], $criteria);
        $reportTypeMappingTable = MigrationDb::getMappingTable('type_constat');

        foreach ($oldActions as $oldAction) {
            $oldReportTypeId = $oldAction[$actionMappingTable['rel_constat']];
            if ($oldReportTypeId) {
                $criteria = [$reportTypeMappingTable['id'] => $oldReportTypeId];
                $oldReportType = $this->migrationRepository
                    ->getOneBy(MigrationRepository::$oldDBConnection, $reportTypeMappingTable['table'], $criteria);
                $oldLabel = $oldReportType[$reportTypeMappingTable['libelle']];
                $oldLabel = strtolower(MigrationDb::utf8Encode($oldLabel));
                $report = null;
                if ($oldLabel === 'vu') {
                    $report = $this->createSeenReport($oldAction, $actionMappingTable);
                    $this->entityManager->persist($report);
                    $newFurniture->addReport($report);
                }
                // this after validation
//                elseif ($oldLabel === 'non vu') {
//                  $report = $this->createAction($oldAction, $actionMappingTable);
//                }
            }
            // this after validation
//            $i++;
//            if ($i % 100 === 0) {
//                $this->entityManager->flush();
//            }
        }
//       this after validation  $this->entityManager->flush();
    }

    private function createSeenReport(array $oldAction, array $actionMappingTable)
    {
        $oldComment = $oldAction[$actionMappingTable['commentaire']];
        $report = (new Report())->setComment(MigrationDb::utf8Encode($oldComment));
        $subTypeMappingTable = MigrationDb::getMappingTable('sous_type_constat');
        $oldSubTypeId = $oldAction[$actionMappingTable['rel_sous_type_constat']];
        if ($oldSubTypeId) {
            $criteria = [$subTypeMappingTable['id'] => $oldSubTypeId];
            $oldSubType = $this->migrationRepository
                ->getOneBy(MigrationRepository::$oldDBConnection, $subTypeMappingTable['table'], $criteria);
            $oldLabel = $oldSubType[$subTypeMappingTable['libelle']];
            $oldLabel = strtolower(MigrationDb::utf8Encode($oldLabel));
            $label = '';
            if (strpos($oldLabel, 'bon') !== false) {
                $label = ReportSubType::TYPE_VUE['bonEtat'];
            } elseif (strpos($oldLabel, 'commentaire') !== false) {
                $label = ReportSubType::TYPE_VUE['voirCommentaire'];
            }
            $subReportType = $this->entityManager->getRepository(ReportSubType::class)
                ->findOneBy(['label' => $label]);
            $report->setReportSubType($subReportType);
        }
        $date = $oldAction[$actionMappingTable['date']];
        if ($date) {
            $report->setDate(new DateTime($date));
        }
        return $report;
    }

    private function createAction(array $oldAction, array $actionMappingTable)
    {
        $reportSubType = $this->entityManager->getRepository(ReportSubType::class)
            ->findOneBy(['label' => ReportSubType::TYPE_NON_VUE['nonVue']]);
        $report = (new Report())->setReportSubType($reportSubType);
        $oldComment = $oldAction[$actionMappingTable['commentaire']];
        $newAction = (new Action())->setComment(MigrationDb::utf8Encode($oldComment));
        $date = $oldAction[$actionMappingTable['date']];
        if ($date) {
            $report->setDate(new DateTime($date));
        }
        $report->addAction($newAction);
    }

}