<?php

namespace App\Command;

use App\Command\Utils\ExcelLogger;
use App\Command\Utils\InitializationScriptService;
use App\Command\Utils\MigrationDb;
use App\Command\Utils\MigrationRepository;
use App\Command\Utils\MigrationTrait;
use App\Command\Utils\ObjectDimensionsTrait;
use App\Command\Utils\ReportActionTrait;
use App\Entity\ArtWork;
use App\Entity\ArtWorkLog;
use App\Entity\Attachment;
use App\Entity\Author;
use App\Entity\Denomination;
use App\Entity\Depositor;
use App\Entity\DepositStatus;
use App\Entity\Furniture;
use App\Entity\MaterialTechnique;
use App\Entity\PropertyStatus;
use App\Services\LoggerService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Stopwatch\Stopwatch;

class MigrateObjectsCommand extends Command
{
    use MigrationTrait;
    use ObjectDimensionsTrait;
    use ReportActionTrait;

    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:migrate:dynamic';

    protected const GROUP = ['statut', 'objet_mobilier', 'fichier_joint', 'log_oeuvre', 'matiere_technique', 'auteur'];
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
    private $excelLogger;
    private $initializationScriptService;

    public function __construct(
        EntityManagerInterface $entityManager,
        MigrationRepository $migrationRepository,
        Stopwatch $stopwatch,
        LoggerService $loggerService,
        ExcelLogger $excelLogger,
        InitializationScriptService $initializationScriptService
    )
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->connection = $this->entityManager->getConnection();
        $this->migrationRepository = $migrationRepository;
        $this->stopwatch = $stopwatch;
        $this->loggerService = $loggerService;
        $this->excelLogger = $excelLogger;
        $loggerService->init('db_migration');
        $this->excelLogger->initFile('objectMigration');
        $this->initializationScriptService = $initializationScriptService;
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
            'Migrate Furniture Data From Access DB',
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

        // todo initilization script
        $this->initializationScriptService->initializeTypes();
        $this->createFurniture($output);

        // todo drop old columns after migrating Furniture
//        $this->dropOldColumnsStaticData();

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

    private function dropOldColumnsStaticData()
    {
        foreach (MigrateStaticDataCommand::GROUPS as $group) {
            $this->migrationRepository->dropOldColumn($group);
        }
    }
    // todo delete
    private function test($id)
    {
        $table = MigrationDb::OEUVRES['table'];
        return $this->migrationRepository->getOneBy(MigrationRepository::$oldDBConnection, $table, ['C_MGPAM' => $id]);
    }

    private function createFurniture(OutputInterface $output)
    {
        $entity = 'art_work';
        $this->migrationRepository->dropNewTables(self::GROUP);
        $mappingTable = [
            'id' => 'C_MGPAM',
            'table' => 'OEUVRES',
            'rel_denomination' => 'C_DENOMINATION',
            'rel_field' => 'C_DOMAINE',
            'rel_era' => 'C_EPOQUE',
            'rel_style' => 'C_STYLE',
            'title' => 'OE_TITRE',
            'number_of_unit' => 'OE_NB',
            'description' => 'OE_REPRISE',
        ];
        $oldEntities = $this->migrationRepository
            ->getAll(MigrationRepository::$oldDBConnection, $mappingTable['table']);
        $foundError = false;
        $rowCount = 1;
        $columns = array_keys($this->furnitureDimensions($oldEntities[0], new ArtWork()));
        $this->excelLogger->write($columns, 1);
        foreach ($oldEntities as $oldEntity) {
            // todo for testing
//            $oldEntity = $this->test(3906);

            $newEntity = $this->createEntity($entity, $oldEntity, $mappingTable, $foundError);

            $this->addAuthor($oldEntity, $newEntity);
            $this->setMaterialTechnique($oldEntity, $newEntity, $mappingTable);
            $this->setStatus($oldEntity, $newEntity);
            $this->addAttachments($oldEntity, $newEntity);
            $dimensionError = false;
            $this->findDimensions($oldEntity, $newEntity, $dimensionError);

//
            // todo should fix the logic of migration
//            $this->addConstat($oldEntity, $newEntity);
//            $this->addReports($oldEntity, $newEntity);

            // todo object log isn't correct
//            $this->addObjectLog($oldEntity, $newEntity);

            $this->entityManager->persist($newEntity);

            // here we add the new entity ID after persisting
            $rowCount++;
            $this->logFurnitureDimensions($oldEntity, $newEntity, $rowCount, $dimensionError);
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
        $this->excelLogger->save();
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
                    $relatedClassName = $this->getClass($relatedClass);
                    $relatedTableName = $this->entityManager->getClassMetadata($relatedClassName)->getTableName();
                    $relatedEntityId = $this->getRelatedEntity($oldEntity, $relatedTableName, $foundError);
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

    private function addAuthor($oldFurniture, Furniture &$newFurniture)
    {
        $mappingTable = MigrationDb::getMappingTable('auteur');
        $authorLastName = MigrationDb::utf8Encode($oldFurniture[$mappingTable['nom']]);
        $authorFirstName = MigrationDb::utf8Encode($oldFurniture[$mappingTable['prenom']]);
        // sometimes the author lastname and firstname are null so we don't create an author
        if (!$authorLastName && !$authorFirstName) {
            return;
        }
        $author = $this->entityManager->getRepository(Author::class)
            ->findOneBy(['lastName' => $authorLastName, 'firstName' => $authorFirstName]);
        if (!$author) {
            $author = (new Author())->setLastName($authorLastName)->setFirstName($authorFirstName);
            $this->entityManager->persist($author);
            $this->entityManager->flush();
        }
        $newFurniture->addAuthor($author);
    }

    private function setMaterialTechnique($oldFurniture, Furniture &$newFurniture, $mappingTable)
    {
        $materialTechniqueLabel = $oldFurniture[MigrationDb::MATIERE['libelle']];
        if (!$materialTechniqueLabel) {
            return;
        }

        $materialTechniqueLabel = MigrationDb::utf8Encode($materialTechniqueLabel);
        $materialTechnique = $this->entityManager->getRepository(MaterialTechnique::class)
            ->findOneBy(['label' => $materialTechniqueLabel]);

        $oldDenominationId = $oldFurniture[$mappingTable['rel_denomination']];
        $denomination = null;
        if ($oldDenominationId) {
            $criteria = ['old_id' => $oldDenominationId];
            $denomination = $this->migrationRepository
                ->getOneBy(MigrationRepository::$newDBConnection, 'denomination', $criteria);
            $denomination = $this->entityManager->getRepository(Denomination::class)
                ->find($denomination['id']);
        }

        if (!$materialTechnique) {
            $materialTechnique = (new MaterialTechnique())->setLabel($materialTechniqueLabel);
            $this->entityManager->persist($materialTechnique);
            $this->entityManager->flush();
        }
        if ($denomination) {
            if (!$materialTechnique->getDenominations()->contains($denomination)) {
                $materialTechnique->addDenomination($denomination);
                $this->entityManager->persist($materialTechnique);
                $this->entityManager->flush();
            } else {
                $materialTechnique = $denomination->getMaterialTechniqueByLabel($materialTechniqueLabel);
            }
        }
        $newFurniture->setMaterialTechnique($materialTechnique);
    }

    private function setStatus($oldFurniture, Furniture &$newFurniture)
    {
        $foundError = false;
        $depositDate = $oldFurniture['OE_DATEDEPOT'];
        $oldDepositor = intval($oldFurniture['C_DEPOSANT']);
        $status = null;
        if (($oldDepositor === 6) || is_null($oldDepositor)) {
            $status = new PropertyStatus();
            if ($depositDate) {
                $status->setEntryDate(new DateTime($depositDate));
            }
            $status->setPropOnePercent($oldFurniture['OE_UNPOURCENT']);
        } else {
            $status = new DepositStatus();
            $relatedClass = 'deposant';
            $relatedEntity = null;
            $relatedEntityId = $this->getRelatedEntity($oldFurniture, $relatedClass, $foundError);
            if ($relatedEntityId) {
                $relatedEntity = $this->entityManager->getRepository(Depositor::class)
                    ->find($relatedEntityId);
                $status->setDepositor($relatedEntity);
            }
            if ($depositDate) {
                $status->setDepositDate(new DateTime($depositDate));
            }
        }
        $status->setComment(MigrationDb::utf8Encode($oldFurniture['OE_DEPOT']));
        $this->entityManager->persist($status);
        $newFurniture->setStatus($status);
    }

    private function addAttachments($oldFurniture, Furniture &$newFurniture)
    {
        $mappingTable = MigrationDb::getMappingTable('fichier_joint');
        $oldRelation = $oldFurniture[$mappingTable['rel_objet_mobilier']];
        $criteria = [$mappingTable['rel_objet_mobilier'] => $oldRelation];
        $oldAttachements = $this->migrationRepository
            ->getBy(MigrationRepository::$oldDBConnection, $mappingTable['table'], $criteria);
        foreach ($oldAttachements as $attachement) {
            $newAttachement = (new Attachment())
                ->setComment(MigrationDb::utf8Encode($attachement[$mappingTable['commentaire']]))
                ->setLink(MigrationDb::utf8Encode($attachement[$mappingTable['lien']]))
                ->setPrincipleImage($attachement[$mappingTable['image_principale']]);
            $date = $attachement[$mappingTable['date']];
            if ($date) {
                $newAttachement->setDate(new  DateTime($date));
            } else {
                $newAttachement->setDate(new  DateTime());
            }
            $this->entityManager->persist($newAttachement);
            $newFurniture->addAttachment($newAttachement);
        }
        unset($oldAttachements);
    }
}
