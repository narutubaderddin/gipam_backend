<?php

namespace App\Command\Utils;

use App\Entity\Action;
use App\Entity\Furniture;
use App\Entity\MovementActionType;
use App\Entity\MovementType;
use App\Entity\Report;
use App\Entity\ReportSubType;
use App\Entity\ReportType;

trait ReportActionTrait
{
    private function createActionMouvementTypes()
    {
        $tables = ['type_action', 'type_mouvement', 'type_mouvement_action'];
        $this->migrationRepository->dropNewTables($tables);
        foreach (MovementType::LIBELLE as $key => $label) {
            $mouvementType = (new MovementType())->setLabel($label);
            $this->entityManager->persist($mouvementType);
            if (isset(MovementActionType::LIBELLE[$key])) {
                $actionMouvementTypes = MovementActionType::LIBELLE[$key];
                foreach ($actionMouvementTypes as $actionMouvementLabel) {
                    $actionMouvementType = (new MovementActionType())
                        ->setLabel($actionMouvementLabel)
                        ->setMovementType($mouvementType);
                    $this->entityManager->persist($actionMouvementType);
                }
            }
        }
        $this->entityManager->flush();
        $this->entityManager->clear();
    }

    private function createReportType()
    {
        $tables = ['constat', 'sous_type_constat', 'type_constat'];
        $this->migrationRepository->dropNewTables($tables);
        foreach (ReportType::LIBELLE as $key => $label) {
            $type = (new ReportType())->setLabel($label);
            $this->entityManager->persist($type);
            $subLabels = ReportSubType::LIBELLE[$key];
            foreach ($subLabels as $subLabel) {
                $subType = (new ReportSubType())
                    ->setLabel($subLabel)
                    ->setReportType($type);
                $this->entityManager->persist($subType);
            }
        }
        $this->entityManager->flush();
        $this->entityManager->clear();
    }

    private function addReports($oldFurniture, Furniture &$newFurniture)
    {
        $actionMappingTable = MigrationDb::getMappingTable('action');
        $oldRelation = $oldFurniture[$actionMappingTable['rel_objet_mobilier']];
        $criteria = [$actionMappingTable['rel_objet_mobilier'] => $oldRelation];
        $oldActions = $this->migrationRepository
            ->getBy(MigrationRepository::$oldDBConnection, $actionMappingTable['table'], $criteria);
        $reportTypeMappingTable = MigrationDb::getMappingTable('type_constat');
        $i = 1;
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
                } elseif ($oldLabel === 'non vu') {
//                    $report = $this->createAction($oldAction, $actionMappingTable);
                }
            }
//            $i++;
//            if ($i % 100 === 0) {
//                $this->entityManager->flush();
//            }
        }
//        $this->entityManager->flush();
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
        } else {

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