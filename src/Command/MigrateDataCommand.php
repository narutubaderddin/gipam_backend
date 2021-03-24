<?php

namespace App\Command;

use App\Command\Utils\MigrationDb;
use App\Command\Utils\MigrationRepository;
use App\Services\LoggerService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Stopwatch\Stopwatch;

class MigrateDataCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:migrate';

    // number of logic group
    protected const GROUPS = [
        1 => ['ministry', 'establishment', 'service', 'correspondent',],
        2 => ['region', 'departement', 'commune', 'site', 'building',],
        3 => [/*'deposittype', 'style', 'era', 'domaine', */'denomination',],
        4 => ['deposittype', 'depositor'],
//        4 => ['actiontype', 'report', 'action', 'depositor'],
    ];
    /**
     * @var SymfonyStyle
     */
    private $io;

    private $entityManager;
    private $migrationRepository;
    private $stopwatch;
    private $logger;
    private $connection;
    private $loggerService;

    public function __construct(
        EntityManagerInterface $entityManager,
        MigrationRepository $migrationRepository,
        Stopwatch $stopwatch,
        LoggerInterface $logger,
        LoggerService $loggerService
    ) {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->connection = $this->entityManager->getConnection();
        $this->migrationRepository = $migrationRepository;
        $this->stopwatch = $stopwatch;
        $this->logger = $logger;
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
            $output->writeln([
                '',
                '===================================================',
                'Migrate Data From Access DB to MySQL Canceled! Bye!',
            ]);
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
                $output->writeln([
                    '',
                    '===================================================',
                    'Migrate Data From Access DB to MySQL Canceled! Bye!',
                ]);
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
                $output->writeln([
                    '',
                    '===================================================',
                    'Migrate Data From Access DB to MySQL Canceled! Bye!',
                ]);
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
        $this->dropTables($group);
        $this->addOldColumn($group);
        $foundError = false;
        $this->loggerService->addInfo("================ Migrating group " . $groupNumber . " ================");
        foreach ($group as $entity) {
            $mappingTable = MigrationDb::TABLE_NAME[$entity];
            $results = $this->migrationRepository
                ->getAll(MigrationRepository::$oldDBConnection, $mappingTable['table']);
            $rowCount = 1;
            $columns = $this->getColumns($mappingTable);
            $sqlInsert = $this->migrationRepository->createInsertStatement($entity, $columns);
            foreach ($results as $oldEntity) {
                $newEntity = $this->createEntity($entity, $oldEntity, $mappingTable, $rowCount, $foundError);
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
                $this->loggerService->addWarning($errorMsg);
                $foundError = true;
            }
            $output->writeln([
                '===================================================',
                'Migrate ' . $rowCount . ' / ' . count($results) . ' ' . ucfirst($entity),
                '===================================================',
            ]);
            unset($results);
            unset($columns);
            unset($sqlInsert);
            unset($mappingTable);
            gc_collect_cycles();
        }
        if ($foundError) {
            $output->writeln('<error> Migration Errors for group ' . $groupNumber .
                ' please see log in : var/log/dev.log </error>');
        }
//        $this->dropOldColumn($group);
    }

    private function dropTables(array $tables)
    {
        $tables = array_reverse($tables);

        foreach ($tables as $table) {
            $this->connection->executeQuery("TRUNCATE TABLE " . $table . " RESTART IDENTITY CASCADE");
        }
    }

    private function getOldIdColumns(string $table)
    {
        $mappingTable = MigrationDb::TABLE_NAME[$table];
        $keys = array_keys($mappingTable);
        $i = count($keys) - 1;
        $columns = [];
        while ((strpos($keys[$i], 'old_id') !== false) && $i >= 0) {
            $columns[] = $keys[$i];
            $i--;
        }
        return array_reverse($columns);
    }

    private function addOldColumn(array $tables)
    {
        foreach ($tables as $table) {
            // todo : here we add the temporary column
            $columns = $this->getOldIdColumns($table);
            foreach ($columns as $column) {
                $this->connection->executeQuery("ALTER TABLE " . $table . " ADD IF NOT EXISTS " . $column . " VARCHAR");
            }
        }
    }

    private function dropOldColumn(array $tables)
    {
        foreach ($tables as $table) {
            // todo : here we drop the temporary column
            $columns = $this->getOldIdColumns($table);
            foreach ($columns as $column) {
                $this->connection->executeQuery("ALTER TABLE " . $table . " DROP COLUMN IF EXISTS " . $column);
            }
        }
    }

    private function getClass($className)
    {
        return $className = "App\\Entity\\" . ucfirst($className);
    }

    private function getColumns($mappingTable)
    {
        $keys = array_keys($mappingTable);
        $i = count($keys) - 1;
        $columns = [];
        while ((strpos($keys[$i], 'table') === false) && $i >= 0) {
            if (strpos($keys[$i], 'rel_') === false) {
                $columns[] = $keys[$i];
            } else {
                $columns[] = explode('_', $keys[$i])[1] . '_id';
            }
            $i--;
        }
        $columns[] = 'id';
        return array_reverse($columns);
    }

    private function getRelatedEntity(array $oldEntity, string $relatedTableName)
    {
        $relatedTableNameMappingTable = MigrationDb::TABLE_NAME[$relatedTableName];
        if (!MigrationDb::UPPERCASE_NAME) {
            $relatedTableNameMappingTable = array_map('strtolower', $relatedTableNameMappingTable);
        }
        $oldIdColumns = $this->getOldIdColumns($relatedTableName);
        $criteria = [];
        foreach ($oldIdColumns as $oldIdColumn) {
            $criteria[$oldIdColumn] = $oldEntity[$relatedTableNameMappingTable[$oldIdColumn]];
        }
        return $this->migrationRepository
            ->getBy(MigrationRepository::$newDBConnection, $relatedTableName, $criteria);
    }

    private function createEntity($entity, $oldEntity, $mappingTable, $rowCount, bool &$foundError): array
    {
        $newEntity = [];
        $newEntity[] = $rowCount;
        // Here whene converting the DB System to postgres the names of tables and columns are in lowercase
        // Option changed in MigrationDb class
        if (!MigrationDb::UPPERCASE_NAME) {
            $mappingTable = array_map('strtolower', $mappingTable);
        }
        $attributes = array_keys($mappingTable);
        $i = 3;
        while ($i < count($attributes)) {
            $attribute = $attributes[$i];
            if (strpos($attribute, 'rel_') !== false) {
                $relatedClass = explode('_', $attribute)[1];
                $relatedEntityId = $oldEntity[$mappingTable[$attribute]];
                if ($relatedEntityId !== null) {
                    $relatedEntity = $this->getRelatedEntity($oldEntity, $relatedClass);
                    if (count($relatedEntity) >= 1) {
                        if (count($relatedEntity) > 1) {
                            $errorMsg = 'Error in creating ' . $entity . ' old table = ' . $mappingTable['table'] .
                                ' with row number = ' . $rowCount . ' with old id = ' .
                                $oldEntity[$mappingTable['id']] . ' for ' . $mappingTable[$attribute] . ' = ' .
                                $relatedEntityId . ' related class ' . $relatedClass;
                            $this->loggerService->addWarning($errorMsg);
                            $foundError = true;
                        }
                        $newEntity[] = $relatedEntity[0]['id'];
                    } else {
                        $newEntity[] = null;
                    }
                } else {
                    // some times the entity is not related
                    $newEntity[] = null;
                }
                $i++;
                continue;
            }
            $newValue = $oldEntity[$mappingTable[$attribute]];
            if (MigrationDb::USE_ACCESS_DB && is_string($newValue)) {
                $newValue = utf8_encode($newValue);
            }
            $newEntity[] = $newValue;
            $i++;
        }
        return $newEntity;
    }
}
