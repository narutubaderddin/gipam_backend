<?php

namespace App\Command;

use App\Command\Utils\MigrationDb;
use App\Command\Utils\MigrationRepository;
use App\Entity\Building;
use App\Entity\Service;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Stopwatch\Stopwatch;
use Throwable;

class MigrateDataCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:migrate';

    // number of logic group
    protected const GROUPS = [
        1 => ['ministry', 'establishment', 'service', 'correspondent'],
        2 => ['region', 'departement', 'commune', 'site',
            'building',],
        3 => [
            'movement_type', 'correspondent', 'service', 'deposit_type',
            'style', 'era', 'denomination', 'field',
        ],
        4 => ['report_sub_type', 'report', 'action_type', 'action', 'style', 'depositor'],
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

    public function __construct(
        EntityManagerInterface $entityManager,
        MigrationRepository $migrationRepository,
        Stopwatch $stopwatch,
        LoggerInterface $logger
    )
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->migrationRepository = $migrationRepository;
        $this->stopwatch = $stopwatch;
        $this->logger = $logger;
        $this->connection = $this->entityManager->getConnection();
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

        // return this if there was no problem running the command
        return 0;
    }

    private function dropTables(array $tables)
    {
        $tables = array_reverse($tables);

        foreach ($tables as $table) {
            $this->connection->executeQuery("TRUNCATE TABLE " . $table . " RESTART IDENTITY CASCADE");
            // todo : here we add a temporary column to map related entities
            $this->connection->executeQuery("ALTER TABLE " . $table . " ADD IF NOT EXISTS old_id VARCHAR");
        }

    }

    private function getOldIdColumns($table)
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
                $this->connection->executeQuery("ALTER TABLE " . $table . " ADD IF NOT EXISTS ' . $column . ' VARCHAR");
            }
        }
    }

    private function dropOldColumn(array $tables)
    {
//        foreach ($tables as $table) {
//            // todo : here we drop the temporary column
//            $columns = $this->getOldIdColumns($table);
//            foreach ($columns as $column) {
//                $this->connection->executeQuery("ALTER TABLE " . $table . " DROP COLUMN IF EXISTS " . $column);
//            }
//        }
    }

    private function getClass($className)
    {
        return $className = "App\\Entity\\" . ucfirst($className);
    }

    private function createEntity($entity, $oldEntity, $mappingTable, $rowCount, bool &$foundError): array
    {
        $className = $this->getClass($entity);
        $newEntity = [];
        $newEntity[] = $rowCount;
        // lower the field of $mappingTable
        $mappingTable = array_map('strtolower', $mappingTable);
        $attributes = array_keys($mappingTable);
        $i = 3;
        while ($i < count($attributes)) {
            $attribute = $attributes[$i];
            if (strpos($attribute, 'rel_') !== false) {
                $relatedClass = explode('_', $attribute)[1];
                $relatedEntityId = $oldEntity[$mappingTable[$attribute]];
                if ($relatedEntityId !== null) {
                    $relatedEntity = $this->migrationRepository->getByOldID($relatedClass, $relatedEntityId);
                    if (count($relatedEntity) > 1) {
                        dd($relatedEntity, $relatedEntityId, $relatedClass);
                        $errorMsg = 'Error in creating ' . $entity . ' old table = ' . $mappingTable['table'] .
                            ' with row number = ' . $rowCount . ' with old id = ' . $oldEntity[$mappingTable['id']] .
                            ' for ' . $mappingTable[$attribute] . ' = ' . $relatedEntityId .
                            ' related class ' . $relatedClass;
                        $this->logger->info($errorMsg);
                        $foundError = true;
                    }
                    try {
                        $newEntity[] = $relatedEntity[0]['id'];
                    } catch (Throwable $exception) {
                        dd($exception->getMessage(), $relatedEntity, $relatedEntityId);
                    }
                } else {
                    // some times the entity is not related
                    $newEntity[] = null;
                }
                $i++;
                continue;
            }
            $newEntity[] = $oldEntity[$mappingTable[$attribute]];
            $i++;
        }
        return $newEntity;
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


    private function group(int $groupNumber, OutputInterface $output)
    {
        $group = self::GROUPS[$groupNumber];
        $this->dropTables($group);
        $foundError = false;
        $this->logger->info("================\nMigrating group " . $groupNumber . "\n ================");
        foreach ($group as $entity) {
            $mappingTable = MigrationDb::TABLE_NAME[$entity];
            $results = $this->migrationRepository->getAll($mappingTable['table']);
            $rowCount = 1;
            $columns = $this->getColumns($mappingTable);
            $sqlInsert = $this->migrationRepository->createInsertStatement($entity, $columns);
            foreach ($results as $oldEntity) {
                $newEntity = $this->createEntity($entity, $oldEntity, $mappingTable, $rowCount, $foundError);
                $this->migrationRepository->insertNewEntity($sqlInsert, $newEntity);
                $rowCount++;
                // save created entities an empty the Entity Manager to optimize memory consumption
                // and db connection number
                if ($rowCount % 100 === 0) {
                    gc_collect_cycles();
                }
            }
            $rowCount--;
            if ($rowCount !== count($results)) {
                $errorMsg = ucfirst($entity) . " migration entity count error: old entities = " . count($results) .
                    ' new entities = ' . $rowCount;
                $this->logger->info($errorMsg);
                $foundError = true;
            }
            $output->writeln([
                '===================================================',
                'Migrate ' . $rowCount . ' / ' . count($results) . ' ' . ucfirst($entity),
                '===================================================',
            ]);
            gc_collect_cycles();
        }
        if ($foundError) {
            $output->writeln('<error> Migration Errors for group ' . $groupNumber .
                ' please see log in : var/log/dev.log </error>');
        }
        $this->dropOldColumn($group);
    }

//    private function createEntity($entity, $oldEntity, $mappingTable, $rowCount, bool &$foundError)
//    {
//        $className = $this->getClass($entity);
//        $newEntity = new $className();
//        // lower the field of $mappingTable
//        $mappingTable = array_map('strtolower', $mappingTable);
//        $attributes = array_keys($mappingTable);
//        $i = 3;
//        while ($i < count($attributes)) {
//            $attribute = $attributes[$i];
//            if (strpos($attribute, 'rel_') !== false) {
//                $relatedClass = explode('_', $attribute)[1];
//                $relatedEntityMappingTable = array_map('strtolower', MigrationDb::TABLE_NAME[$relatedClass]);
//                $relatedEntityId = $oldEntity[$mappingTable[$attribute]];
//                if ($relatedEntityId !== null) {
//                    $relatedEntity = $this->migrationRepository
//                        ->getById($relatedEntityMappingTable['table'], $mappingTable[$attribute], $relatedEntityId);
//                    $relatedEntityUnique = $relatedEntity[$relatedEntityMappingTable['unique']];
//                    $attributeTable = array_slice($relatedEntityMappingTable, 3, null, true);
//                    $key = array_search($relatedEntityMappingTable['unique'], $attributeTable);
//                    $relatedEntity = $this->entityManager->getRepository($this->getClass($relatedClass))
//                        ->findBy([$key => $relatedEntityUnique]);
//                    if (count($relatedEntity) > 1) {
//                        $errorMsg = 'Error in creating ' . $entity . ' old table = ' . $mappingTable['table'] .
//                            ' with row number = ' . $rowCount . ' with old id = ' . $oldEntity[$mappingTable['id']] .
//                            ' for ' . $mappingTable[$attribute] . ' = ' . $relatedEntityId .
//                            ' related class ' . $relatedClass . ' and unique search = ' . $relatedEntityUnique;
//                        $this->logger->info($errorMsg);
//                        $foundError = true;
//                    }
//                    $setter = 'set' . ucfirst($relatedClass);
//                    try {
//                        $newEntity->$setter($relatedEntity[0]);
//                    } catch (Throwable $exception) {
//                        dd($exception->getMessage(), $relatedEntity, $relatedEntityUnique);
//                    }
//                }
//                $i++;
//                continue;
//            }
//            $setter = 'set' . ucfirst($attribute);
//            $oldValue = $oldEntity[$mappingTable[$attribute]];
//            $newEntity->$setter($oldValue);
//            $i++;
//        }
//        return $newEntity;
//    }

    /**
     * logic for creating Regions, Departments, Communes and Sites
     * @param OutputInterface $output
     * @throws Exception
     */
//    private function group2(OutputInterface $output)
//    {
//        //empty the Region database table
//        $tables = ['building', 'commune', 'departement', 'region', 'site',];
//        $this->dropTables($tables);
//
//        $results = $this->migrationRepository->getAll(MigrationDb::TABLE_NAME['region']);
//        foreach ($results as $region) {
//            $newRegion = (new Region())->setName($region[MigrationDb::REGIONS['name']]);
//            $this->entityManager->persist($newRegion);
//        }
//        $output->writeln([
//            '===================================================',
//            'Migrate ' . count($results) . ' Regions!',
//            '===================================================',
//        ]);
//        $this->entityManager->flush();
//        $this->entityManager->clear();
//
//        $results = $this->migrationRepository->getAll(MigrationDb::TABLE_NAME['departement']);
//        foreach ($results as $department) {
//            $regionId = $department[MigrationDb::DEPARTEMENTS['rel_region']];
//            $region = $this->migrationRepository->getRegionById($regionId);
//            $regionName = $region[MigrationDb::REGIONS['name']];
//            $region = $this->entityManager->getRepository(Region::class)->findOneBy(['name' => $regionName]);
//            $newDepartment = (new Departement())
//                ->setName($department[MigrationDb::DEPARTEMENTS['name']])
//                ->setRegion($region);
//            $this->entityManager->persist($newDepartment);
//        }
//
//        $this->entityManager->flush();
//        $this->entityManager->clear();
//        $output->writeln([
//            '===================================================',
//            'Migrate ' . count($results) . ' Departments!',
//            '===================================================',
//        ]);
//
//        $results = $this->migrationRepository->getAll(MigrationDb::TABLE_NAME['commune']);
//        $i = 1;
//        foreach ($results as $commune) {
//            $departmentId = $commune[MigrationDb::COMMUNES['rel_departement']];
//            $department = $this->migrationRepository->getDepartmentById($departmentId);
//            $departmentName = $department[MigrationDb::DEPARTEMENTS['name']];
//            $department = $this->entityManager->getRepository(Departement::class)
//                ->findOneBy(['name' => $departmentName]);
//            $newCommune = (new Commune())
//                ->setName($commune[MigrationDb::COMMUNES['name']])
//                ->setDepartement($department);
//            $this->entityManager->persist($newCommune);
//            $i++;
//            if ($i === 100) {
//                $this->entityManager->flush();
//                $this->entityManager->clear();
//                gc_collect_cycles();
//                $i = 1;
//            }
//        }
//        $this->entityManager->flush();
//        $this->entityManager->clear();
//
//        $output->writeln([
//            '===================================================',
//            'Migrate ' . count($results) . ' Communes!',
//            '===================================================',
//        ]);
//
//        $results = $this->migrationRepository->getAll(MigrationDb::TABLE_NAME['building']);
//        foreach ($results as $building) {
//            $name = utf8_encode($building[MigrationDb::SITES_6A['name']]);
//            $address = utf8_encode($building[MigrationDb::SITES_6A['address']]);
//            $newBuilding = (new Building())
//                ->setName($name)
//                ->setAddress($address)
//                ->setCedex($building[MigrationDb::SITES_6A['cedex']])
//                ->setDistrib($building[MigrationDb::SITES_6A['distrib']]);
//            $communeId = $building[MigrationDb::SITES_6A['rel_commune']];
//            $commune = $this->migrationRepository->getCommuneById($communeId);
//
//            if ($commune) {
//                $communeName = $commune[MigrationDb::COMMUNES['name']];
//                $commune = $this->entityManager->getRepository(Commune::class)
//                    ->findOneBy(['Name' => $communeName]);
//                $newBuilding->setCommune($commune);
//            }
//            $this->entityManager->persist($newBuilding);
//            $i++;
//            if ($i === 100) {
//                $this->entityManager->flush();
//                $this->entityManager->clear();
//                $i = 1;
//            }
//        }
//        $this->entityManager->flush();
//        $this->entityManager->clear();
//
//        $output->writeln([
//            '===================================================',
//            'Migrate ' . count($results) . ' Buildings!',
//            '===================================================',
//        ]);
//
//        $i = 1;
//        $results = $this->migrationRepository->getAll(MigrationDb::TABLE_NAME['site']);
//        foreach ($results as $site) {
//            $label = utf8_encode($site[MigrationDb::SITES['label']]);
//            $newSite = (new Site())->setLabel($label);
//            $this->entityManager->persist($newSite);
//            $i++;
//            if ($i === 100) {
//                $this->entityManager->flush();
//                $this->entityManager->clear();
//                $i = 1;
//            }
//        }
//        $this->entityManager->flush();
//        $this->entityManager->clear();
//        gc_collect_cycles();
//
//        $output->writeln([
//            '===================================================',
//            'Migrate ' . count($results) . ' Sites!',
//            '===================================================',
//        ]);
//    }

//    private function group3(OutputInterface $output)
//    {
//        //empty the Region database table
//        $tables = [
//            'movement', 'movement_type', 'correspondent', 'service', 'deposit_type',
//            'style', 'era', 'denomination', 'field',
//        ];
//
//        $this->dropTables($tables);
//
//        $results = $this->migrationRepository->getAll(MigrationDb::TABLE_NAME['era']);
//        foreach ($results as $era) {
//            $label = utf8_encode($label = $era[MigrationDb::EPOQUES['label']]);
//            $newEra = (new Era())->setLabel($label);
//            $this->entityManager->persist($newEra);
//        }
//        $this->entityManager->flush();
//        $this->entityManager->clear();
//        $output->writeln([
//            '===================================================',
//            'Migrate ' . count($results) . ' Eras!',
//            '===================================================',
//        ]);
//
//        $results = $this->migrationRepository->getAll(MigrationDb::TABLE_NAME['field']);
//        foreach ($results as $field) {
//            $label = utf8_encode($label = $field[MigrationDb::DOMAINES['label']]);
//            $newField = (new Field())->setLabel($label);
//            $this->entityManager->persist($newField);
//        }
//        $this->entityManager->flush();
//        $this->entityManager->clear();
//        $output->writeln([
//            '===================================================',
//            'Migrate ' . count($results) . ' Fields!',
//            '===================================================',
//        ]);
//
//        $results = $this->migrationRepository->getAll(MigrationDb::TABLE_NAME['denomination']);
//        foreach ($results as $denomination) {
//            $fieldId = $denomination[MigrationDb::DENOMINATIONS['rel_field']];
//            $field = $this->migrationRepository->getById(
//                MigrationDb::TABLE_NAME['field'],
//                MigrationDb::DOMAINES['id'],
//                $fieldId
//            );
//            $fieldName = utf8_encode($field[MigrationDb::DOMAINES['label']]);
//            $field = $this->entityManager->getRepository(Field::class)
//                ->findOneBy(['label' => $fieldName]);
//
//            $label = utf8_encode($label = $denomination[MigrationDb::DENOMINATIONS['label']]);
//
//            $newField = (new Denomination())->setLabel($label)->setField($field);
//
//            $this->entityManager->persist($newField);
//        }
//        $this->entityManager->flush();
//        $this->entityManager->clear();
//        $output->writeln([
//            '===================================================',
//            'Migrate ' . count($results) . ' Denominations!',
//            '===================================================',
//        ]);
//
//        $results = $this->migrationRepository->getAll(MigrationDb::TABLE_NAME['style']);
//        foreach ($results as $style) {
//            $label = utf8_encode($label = $style[MigrationDb::STYLES['label']]);
//            $newEntity = (new Style())->setLabel($label);
//            $this->entityManager->persist($newEntity);
//        }
//        $this->entityManager->flush();
//        $this->entityManager->clear();
//        $output->writeln([
//            '===================================================',
//            'Migrate ' . count($results) . ' Styles!',
//            '===================================================',
//        ]);
//
//        $results = $this->migrationRepository->getAll(MigrationDb::TABLE_NAME['deposit_type']);
//        foreach ($results as $type) {
//            $label = utf8_encode($label = $type[MigrationDb::TYPES_DEPOSANTS['label']]);
//            $newEntity = (new DepositType())->setLabel($label);
//            $this->entityManager->persist($newEntity);
//        }
//        $this->entityManager->flush();
//        $this->entityManager->clear();
//        gc_collect_cycles();
//
//        $output->writeln([
//            '===================================================',
//            'Migrate ' . count($results) . ' Deposit Types!',
//            '===================================================',
//        ]);
//    }


}
