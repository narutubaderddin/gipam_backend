<?php

namespace App\Command;

use App\Command\Utils\InitializationScriptService;
use App\Command\Utils\MigrationDb;
use App\Command\Utils\MigrationRepository;
use App\Command\Utils\MigrationTrait;
use App\Entity\Establishment;
use App\Services\LoggerService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * Commnd used to migrate static entities by group or at once see --help for more details
 * Class MigrateDataCommand
 * @package App\Command
 */
class MigrateStaticDataCommand extends Command
{
    use MigrationTrait;

    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:migrate:static';

    // number of logic group
    public const GROUPS = [
        1 => ['ministere', 'etablissement', 'sous_direction', 'service', 'correspondant',],
        2 => ['region', 'departement', 'commune', 'site', 'batiment',],
        3 => ['style', 'epoque', 'domaine', 'denomination', 'type_deposant', 'deposant'],
        4 => ['etablissement'],
    ];

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
        $help = "This command allows you to load data from Access DB.\n";
        $i = 1;
        foreach (self::GROUPS as $group) {
            $group = implode(', ', $group);
            $help = $help . "--group=" . $i . " : " . $group . "\n";
            $i++;
        }
        $this->setDescription("Load data from Access DB. (yes/no)")
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
            'Migrate Data From Access DB',
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
        if (array_key_exists($group, self::GROUPS)) {
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
        } elseif ($group === -1) {
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
        } else {
            $output->writeln("Group number not found bye!");
            return 0;
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
        $endTime = time();

        // here we ask if we want to drop old_id columns needed for mapping the entities
        $continue = $this->io->ask(
            'Do you wish to delete old_id columns ?' .
            'If you will migrate the dynamic tables press Enter or write no otherwise type yes. (yes/no)',
            'no'
        );
        $input->setArgument('continue', $continue);
        $continue = strtolower($continue);
        if ($continue === 'yes') {
            // here we drop all the old_id columns that are used for mapping the relations
            $this->dropOldColumns($group);
        }

        $this->loggerService->logMemoryUsage('Migrating Data Done with : ');
        $this->loggerService->formatPeriod($endTime, $startTime, 'Migrating Data Done in : ');

        // return this if there was no problem running the command
        return 0;
    }

    private function dropOldColumns(int $groupNumber)
    {
        if (array_key_exists($groupNumber, self::GROUPS)) {
            $group = self::GROUPS[$groupNumber];
            $this->migrationRepository->dropOldColumn($group);
        }
        if ($groupNumber === -1) {
            foreach (self::GROUPS as $group) {
                $this->migrationRepository->dropOldColumn($group);
            }
        }
    }

    private function matchDepositorTypes()
    {
        $tableName = 'type_deposant';
        $depositorTypesMatching = [
            1 => "Institution Culturelle Publique",
            3 => "Particuliers",
            4 => "Autres Administrations",
            5 => "Institutions Privées",
        ];

        foreach ($depositorTypesMatching as $key => $value) {
            $this->migrationRepository->update($tableName, ['old_id' => strval($key)], ['libelle' => $value]);
        }
        $newDepositorTypesMatching = [
            "Service des Musées de France",
            "Autres Musées",
            "Etablissements du Ministère de la Culture",
        ];
        $sql = $this->migrationRepository->createInsertStatement($tableName, ['libelle']);
        foreach ($newDepositorTypesMatching as $value) {
            $this->migrationRepository->insertNewEntity($sql, [$value]);
        }
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
        if ($groupNumber === 3) {
            $this->matchDepositorTypes();
        }
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
            // some attributes has a default boolean value configured in the mapping table
            if (strpos($attribute, 'default_bool') !== false) {
                $newEntity[] = $mappingTable[$attribute];
                $i++;
                continue;
            }
            // some attributes has a default date value configured in the mapping table
            if (strpos($attribute, 'default_date') !== false) {
                // here when the date field is not found in the old data we make the new date value today
                // the disappearance date in this case will be null
                $value =  $oldEntity[$mappingTable[$attribute]] ?? true;
                $newEntity[] = $this->getDefaultDateValue($attribute, $value);
                $i++;
                continue;
            }
            $newEntity[] = MigrationDb::utf8Encode($oldEntity[$mappingTable[$attribute]]);
            $i++;
        }

        return $newEntity;
    }

    /**
     * @param $attribute
     * @param $value
     * @return bool|DateTime|null
     * @throws Exception
     */
    private function getDefaultDateValue($attribute, $value)
    {
        $defaultValue = null;
        $config = explode('_', $attribute);
        $start = ["debut"];
        $end = ["disparition", "fin"];
        $date = date("d/m/Y");
        if (in_array(end($config), $start)) {
            $defaultValue = $date;
        }
        if (in_array(end($config), $end) && !boolval($value)) {
            $defaultValue = $date;
        }
        return $defaultValue;
    }

    private function getSameInNewDb(array $oldEntity, $newDbTableName)
    {
        $mappingTable = MigrationDb::getMappingTable($newDbTableName);
        $columns = $this->getColumns($mappingTable, false, false);
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

    private function getColumns($mappingTable, $withRelations = true, $withDefault = true)
    {
        $keys = array_keys($mappingTable);
        $i = count($keys) - 1;
        $columns = [];
        while ((strpos($keys[$i], 'table') === false) && $i >= 0) {
            if (strpos($keys[$i], 'default_') !== false) {
                if ($withDefault) {
                    $attribute = explode('_', $keys[$i]);
                    $start = strlen($attribute[0]) + strlen($attribute[1]) + 2;
                    $columns[] = substr($keys[$i], $start);
                }
                $i--;
                continue;
            }
//            if (strpos($keys[$i], 'rel_') === false) {
//                $columns[] = $keys[$i];
//            } elseif ($withRelations) {
//                $columns[] = $this->getRelatedTable($keys[$i]) . '_id';
//            }
            if (strpos($keys[$i], 'rel_') !== false) {
                if ($withRelations) {
                    $columns[] = $this->getRelatedTable($keys[$i]) . '_id';
                }
                $i--;
                continue;
            }
            $columns[] = $keys[$i];
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
