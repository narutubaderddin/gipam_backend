<?php

namespace App\Command;

use App\Command\Utils\MigrationDb;
use App\Command\Utils\MigrationRepository;
use App\Command\Utils\MigrationTrait;
use App\Entity\Action;
use App\Entity\Auteur;
use App\Entity\Constat;
use App\Entity\Deposant;
use App\Entity\Epoque;
use App\Entity\FichierJoint;
use App\Entity\LogOeuvre;
use App\Entity\MatiereTechnique;
use App\Entity\ObjetMobilier;
use App\Entity\SousTypeConstat;
use App\Entity\Statut;
use App\Entity\StatutDepot;
use App\Entity\StatutPropriete;
use App\Entity\TypeConstat;
use App\Services\LoggerService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\TextUI\XmlConfiguration\Migration;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Stopwatch\Stopwatch;

class MigrateObjectsCommand extends Command
{
    use MigrationTrait;

    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:migrate:all';

    protected const GROUP = [/*'statut_depot', 'statut_propriete',*/
        'statut', 'objet_mobilier', 'fichier_joint', 'log_oeuvre'];
    protected const SEPARATOR = '===================================================';
    protected const CANCELED = [
        '',
        self::SEPARATOR,
        'Migrate Data From Access DB Canceled! Bye!',
    ];
    /**
     * @var SymfonyStyle
     */
    private $io;

    private $entityManager;
    private $migrationRepository;
    private $stopwatch;
    private $connection;
    private $loggerService;

    public function __construct(
        EntityManagerInterface $entityManager,
        MigrationRepository $migrationRepository,
        Stopwatch $stopwatch,
        LoggerService $loggerService
    )
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->connection = $this->entityManager->getConnection();
        $this->migrationRepository = $migrationRepository;
        $this->stopwatch = $stopwatch;
        $this->loggerService = $loggerService;
        $loggerService->init('db_migration');
    }

    protected function configure()
    {
        $help = "This command allows you to load OEUVRES table from Access DB To your DB.\n";
        $this->setDescription("Load data from Access DB to your DB. (yes/no)")
            ->setHelp($help)
            ->addArgument('continue', InputArgument::OPTIONAL, '', 'no');
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        // SymfonyStyle is an optional feature that Symfony provides so you can
        // apply a consistent look to the commands of your application.
        // See https://symfony.com/doc/current/console/style.html
        $this->io = new SymfonyStyle($input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Migrate Data From Access DB to your DB',
            '====================================',
            '',
        ]);

        $continue = $this->io->ask('This commande will delete all the saved data!' .
            'for the selected group' .
            ' Are you sure you wish to continue? (yes/no)', 'no');
        $input->setArgument('continue', $continue);
        $continue = strtolower($continue);
        if ($continue !== 'yes') {
            $output->writeln(self::CANCELED);
            return 0;
        }
        $this->loggerService->addInfo("================ Migrating Furniture ================");
        $this->loggerService->addInfo(self::SEPARATOR);
        $startTime = time();
        $this->stopwatch->start('export-data');

        $this->createReportType();
        $this->createFurniture($output);

        $this->stopwatch->stop('export-data');

        $duration = $this->stopwatch->getEvent('export-data')->getDuration() / 60000;
        $memory = $this->stopwatch->getEvent('export-data')->getMemory() / 1000000;
        $output->writeln([
            '====================',
            'Migrating Data Done!',
            "Time: " . sprintf("%.2f", $duration) . " minutes, Memory: " . sprintf("%.2f", $memory) . " MB",
            '',
        ]);
        $this->loggerService->logMemoryUsage('Migrating Data Done with : ');
        $endTime = time();
        $this->loggerService->formatPeriod($endTime, $startTime, 'Migrating Data Done in : ');
        // return this if there was no problem running the command
        return 0;
    }

    private function test($id)
    {
        $table = MigrationDb::OEUVRES['table'];
        $ret = $this->migrationRepository->getBy(MigrationRepository::$oldDBConnection, $table, ['C_MGPAM' => $id]);
        return $ret[0];
    }

    private function createFurniture(OutputInterface $output)
    {
        $entity = 'oeuvre_art';
        $this->migrationRepository->dropNewTables(self::GROUP);
        $mappingTable = MigrationDb::getMappingTable($entity);
        $oldEntities = $this->migrationRepository
            ->getAll(MigrationRepository::$oldDBConnection, $mappingTable['table']);
        $foundError = false;
        $rowCount = 1;
        foreach ($oldEntities as $oldEntity) {

            // todo for testing
//            $oldEntity = $this->test(1087);
            $newEntity = $this->createEntity($entity, $oldEntity, $mappingTable, $foundError);
//            $this->findDimensions($oldEntity, $newEntity);
//            $this->addConstat($oldEntity, $newEntity);

            $this->addAuthor($oldEntity, $newEntity);
            $this->setMaterialTechnique($oldEntity, $newEntity);
            $this->setStatus($oldEntity, $newEntity);
            $this->addObjectLog($oldEntity, $newEntity);
            $this->addAttachments($oldEntity, $newEntity);
            $this->addReports($oldEntity, $newEntity);

            $this->entityManager->persist($newEntity);
            $rowCount++;
            if ($rowCount % 100 === 0) {
                $this->entityManager->flush();
                $this->entityManager->clear();
                gc_collect_cycles();
            }
            unset($newEntity);
        }
        $this->entityManager->flush();
        $this->entityManager->clear();
        $rowCount--;
        if ($rowCount !== count($oldEntities)) {
            $errorMsg = ucfirst($entity) . " migration entity count error: old entities = " . count($oldEntities) .
                ' new entities = ' . $rowCount;
            $this->loggerService->addInfo($errorMsg);
            $foundError = true;
        }
        $message = 'Migrate ' . $rowCount . ' / ' . count($oldEntities) . ' ' .
            ucfirst($this->getClass($entity, false));
        unset($oldEntities);
        gc_collect_cycles();
        $output->writeln([
            $message,
            self::SEPARATOR,
        ]);
        $this->loggerService->addInfo($message);
        $this->loggerService->addInfo(self::SEPARATOR);
    }

    private function createEntity($entity, $oldEntity, $mappingTable, bool &$foundError)
    {
        $className = $this->getClass($entity);
        $newEntity = new $className();
        $attributes = array_keys($mappingTable);
        $i = 2;
        while ($i < count($attributes)) {
            $attribute = $attributes[$i];
            if (strpos($attribute, 'rel_') !== false) {
                $relatedClass = $this->getRelatedTable($attribute);
                $relatedEntityId = $oldEntity[$mappingTable[$attribute]];
                $relatedEntity = null;
                if ($relatedEntityId !== null) {
                    $relatedEntityId = $this->getRelatedEntity($oldEntity, $relatedClass, $foundError);
                    if ($relatedEntityId) {
                        $relatedEntity = $this->entityManager->getRepository($this->getClass($relatedClass))
                            ->find($relatedEntityId);
                    }
                    if ($foundError) {
                        $errorMsg = 'Error in creating ' . $entity . ' old table = ' . $mappingTable['table'] .
                            ' with old id = ' . $oldEntity[$mappingTable['id']] . ' for ' .
                            $mappingTable[$attribute] . ' = ' . $relatedEntityId . ' related class ' . $relatedClass;
                        $this->loggerService->addInfo($errorMsg);
                    }
                }
                $setter = 'set' . ucfirst($relatedClass);
                $newEntity->$setter($relatedEntity);
                $i++;
                continue;
            }
            $setter = 'set' . $this->getAttribute($attribute);
            $newValue = $oldEntity[$mappingTable[$attribute]];
            $newEntity->$setter(MigrationDb::utf8Encode($newValue));
            $i++;
        }
        return $newEntity;
    }

    private function addReports($oldFurniture, ObjetMobilier &$newFurniture)
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
                    $newFurniture->addConstat($report);
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
        $report = (new Constat())->setCommentaire(MigrationDb::utf8Encode($oldComment));
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
                $label = SousTypeConstat::TYPE_VUE['bonEtat'];
            } elseif (strpos($oldLabel, 'commentaire') !== false) {
                $label = SousTypeConstat::TYPE_VUE['voirCommentaire'];
            }
            $subReportType = $this->entityManager->getRepository(SousTypeConstat::class)
                ->findOneBy(['libelle' => $label]);
            $report->setSousTypeConstat($subReportType);
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
        $reportSubType = $this->entityManager->getRepository(SousTypeConstat::class)
            ->findOneBy(['libelle' => SousTypeConstat::TYPE_NON_VUE['nonVue']]);
        $report = (new Constat())->setSousTypeConstat($reportSubType);
        $oldComment = $oldAction[$actionMappingTable['commentaire']];
        $newAction = (new Action())->setCommentaire(MigrationDb::utf8Encode($oldComment));
        $date = $oldAction[$actionMappingTable['date']];
        if ($date) {
            $report->setDate(new DateTime($date));
        }
        $report->addAction($newAction);
    }

    private function addAuthor($oldFurniture, ObjetMobilier &$newFurniture)
    {
        $mappingTable = MigrationDb::getMappingTable('auteur');
        $authorLastName = MigrationDb::utf8Encode($oldFurniture[$mappingTable['nom']]);
        $authorFirstName = MigrationDb::utf8Encode($oldFurniture[$mappingTable['prenom']]);
        $author = $this->entityManager->getRepository(Auteur::class)
            ->findOneBy(['nom' => $authorLastName, 'prenom' => $authorFirstName]);
        if ($author) {
            $newFurniture->addAuteur($author);
        }
    }

    private function setMaterialTechnique($oldFurniture, ObjetMobilier &$newFurniture)
    {
        $materialTechnique = $oldFurniture[MigrationDb::MATIERE['libelle']];
        $materialTechnique = MigrationDb::utf8Encode($materialTechnique);
        $materialTechnique = $this->entityManager->getRepository(MatiereTechnique::class)
            ->findOneBy(['libelle' => $materialTechnique]);
        if ($materialTechnique) {
            $newFurniture->setMatiereTechnique($materialTechnique);
        }
    }

    private function setStatus($oldFurniture, ObjetMobilier &$newFurniture)
    {
        $foundError = false;
        $depositDate = $oldFurniture['OE_DATEDEPOT'];
        $oldDepositor = intval($oldFurniture['C_DEPOSANT']);
        $status = null;
        if (($oldDepositor === 6) || is_null($oldDepositor)) {
            $status = new StatutPropriete();
            if ($depositDate) {
                $status->setDateEntree(new DateTime($depositDate));
            }
            $status->setPropUnPourCent($oldFurniture['OE_UNPOURCENT']);
        } else {
            $status = new StatutDepot();
            $relatedClass = 'deposant';
            $relatedEntity = null;
            $relatedEntityId = $this->getRelatedEntity($oldFurniture, $relatedClass, $foundError);
            if ($relatedEntityId) {
                $relatedEntity = $this->entityManager->getRepository(Deposant::class)
                    ->find($relatedEntityId);
                $status->setDeposant($relatedEntity);
            }
            if ($depositDate) {
                $status->setDateDepot(new DateTime($depositDate));
            }
        }
        $status->setCommentaire(MigrationDb::utf8Encode($oldFurniture['OE_DEPOT']));
        $this->entityManager->persist($status);
        $newFurniture->setStatut($status);
    }

    private function addObjectLog($oldFurniture, ObjetMobilier &$newFurniture)
    {
        $mappingTable = MigrationDb::getMappingTable('log_oeuvre');
        $date = $oldFurniture[$mappingTable['date']];
        if ($date) {
            $objectLog = (new LogOeuvre())->setDate(new DateTime($date));
            $this->entityManager->persist($objectLog);
            $newFurniture->addLogOeuvre($objectLog);
        }
    }

    private function addAttachments($oldFurniture, ObjetMobilier &$newFurniture)
    {
        $mappingTable = MigrationDb::getMappingTable('fichier_joint');
        $oldRelation = $oldFurniture[$mappingTable['rel_objet_mobilier']];
        $criteria = [$mappingTable['rel_objet_mobilier'] => $oldRelation];
        $oldAttachements = $this->migrationRepository
            ->getBy(MigrationRepository::$oldDBConnection, $mappingTable['table'], $criteria);
        foreach ($oldAttachements as $attachement) {
            $newAttachement = (new FichierJoint())
                ->setCommentaire(MigrationDb::utf8Encode($attachement[$mappingTable['commentaire']]))
                ->setLien(MigrationDb::utf8Encode($attachement[$mappingTable['lien']]))
                ->setImagePrincipale($attachement[$mappingTable['image_principale']]);
            $date = $attachement[$mappingTable['date']];
            if ($date) {
                $newAttachement->setDate(new  DateTime($date));
            }
            $this->entityManager->persist($newAttachement);
            $newFurniture->addFichierJoints($newAttachement);
        }
        unset($oldAttachements);
    }

    private function createReportType()
    {
        $tables = ['constat', 'sous_type_constat', 'type_constat'];
        $this->migrationRepository->dropNewTables($tables);
        foreach (TypeConstat::LIBELLE as $key => $label) {
            $type = (new TypeConstat())->setLibelle($label);
            $this->entityManager->persist($type);
            $subLabels = SousTypeConstat::LIBELLE[$key];
            foreach ($subLabels as $subLabel) {
                $subType = (new SousTypeConstat())
                    ->setLibelle($subLabel)
                    ->setTypeConstat($type);
                $this->entityManager->persist($subType);
            }
        }
        $this->entityManager->flush();
        $this->entityManager->clear();
    }

    private function findDimensions($oldFurniture, ObjetMobilier &$newFurniture)
    {
        $oldEntities = $this->migrationRepository
            ->getOneBy(MigrationRepository::$oldDBConnection, 'OEUVRES', ['C_MGPAM' => 1180]);

        $oldDimensions = MigrationDb::utf8Encode(strtolower($oldEntities['OE_DIM']));

        $dimensions = explode('x', $oldDimensions);

        foreach ($dimensions as $key => $dimension) {
            $dimensions[$key] = trim($dimension);
        }
        $dimensions = preg_replace("/[a-z\s]+/", '', $dimensions);
        if (preg_match("/[0-9\s]cm/", $oldDimensions)) {
            foreach ($dimensions as $key => $dimension) {
                $dimensions[$key] = $dimension . " cm";
            }
        }
        if (preg_match("/[0-9\s]m/", $oldDimensions)) {
            foreach ($dimensions as $key => $dimension) {
                $dimensions[$key] = $dimension . " m";
            }
        }
        if (strpos($oldDimensions, "ø") !== false) {
            dd(true);
        }
        dd(utf8_encode($oldDimensions), $dimensions);
        if (!$oldDimensions) {
            return;
        }
    }
}
