<?php

namespace App\Command;

use App\Command\Utils\MigrationDb;
use App\Command\Utils\MigrationRepository;
use App\Command\Utils\MigrationTrait;
use App\Entity\Auteur;
use App\Entity\Epoque;
use App\Entity\MatiereTechnique;
use App\Entity\ObjetMobilier;
use App\Entity\OeuvreArt;
use App\Services\LoggerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Stopwatch\Stopwatch;

class MigrateDataCommand extends Command
{
    use MigrationTrait;

    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:migrate';

    // number of logic group
    protected const GROUPS = [
        1 => ['ministere', 'etablissement', 'service', 'correspondant',],
        2 => ['region', 'departement', 'commune', 'site', 'batiment',],
        3 => ['style', 'epoque', 'domaine', 'denomination', 'type_deposant', 'deposant'],
        4 => ['type_mouvement', 'type_action', 'auteur', 'matiere_technique',],
//        4 => ['actiontype', 'report', 'action', 'depositor'],
    ];

    protected const SEPARATOR = '===================================================';
    protected const CANCELED = [
        '',
        self::SEPARATOR,
        'Migrate Data From Access DB to MySQL Canceled! Bye!',
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
    ) {
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
        $help = "This command allows you to load data from Access DB To MySQL DB.\n";
        $i = 1;
        foreach (self::GROUPS as $group) {
            $group = implode(', ', $group);
            $help = $help . "--group=" . $i . " : " . $group . "\n";
            $i++;
        }
        $this->setDescription("Load data from Access DB to MySQL DB. (yes/no)")
            ->setHelp($help)
            ->addArgument('continue', InputArgument::OPTIONAL, '', 'no')
            ->addOption(
                'group',
                '-g',
                InputOption::VALUE_REQUIRED,
                'group number otherwise default -1 execute all?',
                -1
            );
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
            'Migrate Data From Access DB to MySQL',
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
        $startTime = time();
        $group = intval($input->getOption('group'));
        if (($group !== -1) || (array_key_exists($group, self::GROUPS))) {
            $msg = implode(', ', self::GROUPS[$group]);
            $continue = $this->io->ask('Loading : ' . $msg . ' (yes/no)', 'no');
            $input->setArgument('continue', $continue);
            $continue = strtolower($continue);
            if ($continue !== 'yes') {
                $output->writeln(self::CANCELED);
                return 0;
            }
            $startTime = time();
            $this->stopwatch->start('export-data');
            $this->group(intval($group), $output);
        } else {
            $continue = $this->io->ask('This commande will delete all the saved data!' .
                ' Are you sure you wish to continue? (yes/no)', 'no');
            $input->setArgument('continue', $continue);
            $continue = strtolower($continue);
            if ($continue !== 'yes') {
                $output->writeln(self::CANCELED);
                return 0;
            }
            $this->stopwatch->start('export-data');
            foreach (self::GROUPS as $key => $GROUP) {
                $this->group($key, $output);
            }
        }
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

    private function group(int $groupNumber, OutputInterface $output)
    {
        $group = self::GROUPS[$groupNumber];
        $this->migrationRepository->dropNewTables(array_reverse($group));
        $this->migrationRepository->addOldColumn($group);
        $foundError = false;
        $foundWarning = false;
        $this->loggerService->addInfo("================ Migrating group " . $groupNumber . " ================");
        $this->loggerService->addInfo(self::SEPARATOR);
        foreach ($group as $entity) {
            $mappingTable = MigrationDb::getMappingTable($entity);
            $results = $this->migrationRepository
                ->getAll(MigrationRepository::$oldDBConnection, $mappingTable['table']);
            $rowCount = 1;
            $columns = $this->getColumns($mappingTable);
            $sqlInsert = $this->migrationRepository->createInsertStatement($entity, $columns);
            foreach ($results as $oldEntity) {
                $sameIdEntities = $this->getSameInNewDb($oldEntity, $entity);
                if (!empty($sameIdEntities)) {
                    $foundWarning = true;
                    $this->loggerService->addInfo(
                        'Same entity found id = ' . $oldEntity[$mappingTable['id']] .
                        ' table name is ' . $entity
                    );
                    unset($sameIdEntities);
                    continue;
                }
                $newEntity = $this->createEntity($entity, $oldEntity, $mappingTable, $foundError);
                // Here if all columns are null we cancel adding the element
                if (empty(array_filter($newEntity))) {
                    unset($newEntity);
                    continue;
                }
                $this->migrationRepository->insertNewEntity($sqlInsert, $newEntity);
                $rowCount++;
                if ($rowCount % 100 === 0) {
                    gc_collect_cycles();
                }
                unset($newEntity);
            }
            $rowCount--;
            if ($rowCount !== count($results)) {
                $errorMsg = ucfirst($entity) . " migration entity count error: old entities = " . count($results) .
                    ' new entities = ' . $rowCount;
                $this->loggerService->addInfo($errorMsg);
                $foundError = true;
            }
            $message = 'Migrate ' . $rowCount . ' / ' . count($results) . ' ' . ucfirst($entity);
            $output->writeln([
                $message,
                self::SEPARATOR,
            ]);
            $this->loggerService->addInfo($message);
            $this->loggerService->addInfo(self::SEPARATOR);
            unset($results);
            unset($columns);
            unset($sqlInsert);
            unset($mappingTable);
            unset($message);
            gc_collect_cycles();
        }
        $this->printWarning($foundWarning, $groupNumber, $output);
        $this->printError($foundError, $groupNumber, $output);

        // here we drop all the old_id columns that are used for mapping the relations
//        $this->migrationRepository->dropOldColumn($group);
    }

    private function createEntity($entity, $oldEntity, $mappingTable, bool &$foundError): array
    {
        $newEntity = [];
        $attributes = array_keys($mappingTable);
        $i = 2;
        // check if there is an entity created with same old_id

        while ($i < count($attributes)) {
            $attribute = $attributes[$i];
            if (strpos($attribute, 'rel_') !== false) {
                $relatedClass = $this->getRelatedTable($attribute);
                $relatedEntityId = $oldEntity[$mappingTable[$attribute]];
                if ($relatedEntityId !== null) {
                    $newEntity[] = $this->getRelatedEntity($oldEntity, $relatedClass, $foundError);
                    if ($foundError) {
                        $errorMsg = 'Error in creating ' . $entity . ' old table = ' . $mappingTable['table'] .
                            ' with old id = ' . $oldEntity[$mappingTable['id']] . ' for ' .
                            $mappingTable[$attribute] . ' = ' . $relatedEntityId . ' related class ' . $relatedClass;
                        $this->loggerService->addInfo($errorMsg);
                    }
                } else {
                    // some times the entity is not related
                    $newEntity[] = null;
                }
                $i++;
                continue;
            }
            $newValue = $oldEntity[$mappingTable[$attribute]];
            $newEntity[] = MigrationDb::utf8Encode($newValue);
            $i++;
        }

        return $newEntity;
    }

    private function getSameInNewDb(array $oldEntity, $newDbTableName)
    {
        $mappingTable = MigrationDb::getMappingTable($newDbTableName);
        $columns = $this->getColumns($mappingTable, false);
        $oldColumns = [];
        foreach ($columns as $column) {
            if (isset($mappingTable[$column])) {
                $oldColumns[] = $mappingTable[$column];
            }
        }
        $criteria = $this->extractFromArray($oldEntity, $oldColumns, $columns);
        if (!empty($criteria)) {
            return $this->migrationRepository
                ->getBy(MigrationRepository::$newDBConnection, $newDbTableName, $criteria);
        }
        return [];
    }

    private function getColumns($mappingTable, $withRelations = true)
    {
        $keys = array_keys($mappingTable);
        $i = count($keys) - 1;
        $columns = [];
        while ((strpos($keys[$i], 'table') === false) && $i >= 0) {
            if (strpos($keys[$i], 'rel_') === false) {
                $columns[] = $keys[$i];
            } elseif ($withRelations) {
                $columns[] = $this->getRelatedTable($keys[$i]) . '_id';
            }
            $i--;
        }
        return array_reverse($columns);
    }

    private function extractFromArray(array $input, array $keys, array $newKeys): array
    {
        $output = [];
        $iterator = 0;
        $i = array_keys($keys);
        while ($iterator < count($keys)) {
            $key = $keys[$i[$iterator]];
            if (isset($input[$key])) {
                $newKey = $newKeys[$iterator];
                $output[$newKey] = MigrationDb::utf8Encode($input[$key]);
            }
            $iterator++;
        }
        return $output;
    }
}
