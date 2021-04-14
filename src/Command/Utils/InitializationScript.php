<?php

namespace App\Command\Utils;

use App\Entity\MovementActionType;
use App\Entity\MovementType;
use App\Entity\ReportSubType;
use App\Entity\ReportType;
use Doctrine\ORM\EntityManagerInterface;

class InitializationScript
{
    use MigrationTrait;

    private $entityManager;
    private $migrationRepository;

    public function __construct(EntityManagerInterface $entityManager, MigrationRepository $migrationRepository)
    {
        $this->entityManager = $entityManager;
        $this->migrationRepository = $migrationRepository;
    }

    public function initializeTypes()
    {
        $this->createSimpleTypes();
        $this->createActionMouvementTypes();
        $this->createReportType();
    }

    private function createActionMouvementTypes()
    {
        $tables = ['type_mouvement', 'type_mouvement_action'];
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

    private function createSimpleTypes()
    {
        $entities = ['AuthorType', 'PropertyStatusCategory', 'LocationType', 'EntryMode', 'ActionType'];
        foreach ($entities as $entity) {
            $className = $this->getClass($entity);
            $tableName = $this->entityManager->getClassMetadata($className)->getTableName();
            $this->migrationRepository->dropNewTables([$tableName]);
            foreach ($className::LABEL as $label) {
                $newTypeEntity = new $className;
                $newTypeEntity->setLabel($label);
                $this->entityManager->persist($newTypeEntity);
            }
        }
        $this->entityManager->flush();
        $this->entityManager->clear();
    }
}