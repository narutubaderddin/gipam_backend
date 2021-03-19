<?php

namespace App\Command;

//ini_set('memory_limit', '3G');

use App\Entity\Building;
use App\Entity\Commune;
use App\Entity\Denomination;
use App\Entity\Departement;
use App\Entity\DepositType;
use App\Entity\Era;
use App\Entity\Field;
use App\Entity\Region;
use App\Entity\Site;
use App\Entity\Style;
use App\Repository\MigrationRepository;
use App\Utilities\MigrationDb;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MigrateDataCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:data:migrate';

    // number of logic diagram
    protected const GROUPS = [
//        1 => "Create Ministries, Establishments, Services and Correspondents",
        2 => "Create Regions, Departments, Communes and Sites",
        3 => "Create Fields, Denominations, Eras, Styles, Depositors",
//        4 => "ReportSubType, Report, ActionType, Actions, Styles, Depositors",
    ];
    /**
     * @var SymfonyStyle
     */
    private $io;

    private $entityManager;
    private $migrationRepository;

    public function __construct(EntityManagerInterface $entityManager, MigrationRepository $migrationRepository)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->migrationRepository = $migrationRepository;
    }

    protected function configure()
    {
        $help = "This command allows you to load data from Access DB To MySQL DB.\n";
        $i = 1;
        foreach (self::GROUPS as $group) {
            $help = $help . "--group=" . $i . " : " . $group . "\n";
            $i++;
        }
        $this->setDescription("Load data from Access DB to MySQL DB. (yes/no)")
            ->setHelp($help)
            ->addArgument('continue', InputArgument::OPTIONAL, '', 'no')
            ->addOption(
                'group',
                '-l',
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
            $continue = $this->io->ask('Loading : ' . self::GROUPS[$group] . '(yes/no)', 'no');
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
            $function = 'diagram' . intval($group);
            $this->$function($output);
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
            $this->diagram2($output);
            $this->diagram3($output);
//            for ($i = 0; $i < count(self::GROUPS); $i++) {
//                $function = 'diagram' . ($i + 1);
//                $this->$function();
//            }
        }
        $output->writeln([
            '====================',
            'Migrating Data Done!',
            '',
        ]);

        // return this if there was no problem running the command
        return 0;
    }

    private function dropTables(array $tables)
    {
        $connection = $this->entityManager->getConnection();
        $platform = $connection->getDatabasePlatform();
        foreach ($tables as $table) {
            $truncateSql = $platform->getTruncateTableSQL($table, true);
            $connection->executeStatement($truncateSql);
        }
    }

    /**
     * logic for creating Regions, Departments, Communes and Sites
     * @param OutputInterface $output
     * @throws Exception
     */
    private function diagram2(OutputInterface $output)
    {
        //empty the Region database table
        $tables = ['building', 'commune', 'departement', 'region', 'site',];
        $this->dropTables($tables);

        $results = $this->migrationRepository->getRegions();
        $results = $this->migrationRepository->getAll(MigrationDb::TABLE_NAME['region']);
        foreach ($results as $region) {
            $newRegion = (new Region())->setName($region[MigrationDb::REGIONS['name']]);
            $this->entityManager->persist($newRegion);
        }
        $output->writeln([
            '===================================================',
            'Migrate ' . count($results) . ' Regions!',
            '===================================================',
        ]);
        $this->entityManager->flush();
        $this->entityManager->clear();

        $results = $this->migrationRepository->getDepartments();
        $results = $this->migrationRepository->getAll(MigrationDb::TABLE_NAME['departement']);
        foreach ($results as $department) {
            $regionId = $department[MigrationDb::DEPARTEMENTS['rel_region']];
            $region = $this->migrationRepository->getRegionById($regionId);
            $regionName = $region[MigrationDb::REGIONS['name']];
            $region = $this->entityManager->getRepository(Region::class)->findOneBy(['name' => $regionName]);
            $newDepartment = (new Departement())
                ->setName($department[MigrationDb::DEPARTEMENTS['name']])
                ->setRegion($region);
            $this->entityManager->persist($newDepartment);
        }

        $this->entityManager->flush();
        $this->entityManager->clear();
        $output->writeln([
            '===================================================',
            'Migrate ' . count($results) . ' Departments!',
            '===================================================',
        ]);

        $results = $this->migrationRepository->getAll(MigrationDb::TABLE_NAME['commune']);
        $i = 1;
        foreach ($results as $commune) {
            $departmentId = $commune[MigrationDb::COMMUNES['rel_departement']];
            $department = $this->migrationRepository->getDepartmentById($departmentId);
            $departmentName = $department[MigrationDb::DEPARTEMENTS['name']];
            $department = $this->entityManager->getRepository(Departement::class)
                ->findOneBy(['name' => $departmentName]);
            $newCommune = (new Commune())
                ->setName($commune[MigrationDb::COMMUNES['name']])
                ->setDepartement($department);
            $this->entityManager->persist($newCommune);
            $i++;
            if ($i === 100) {
                $this->entityManager->flush();
                $this->entityManager->clear();
                $i = 1;
            }
        }
        $this->entityManager->flush();
        $this->entityManager->clear();
        $output->writeln([
            '===================================================',
            'Migrate ' . count($results) . ' Communes!',
            '===================================================',
        ]);

        $results = $this->migrationRepository->getAll(MigrationDb::TABLE_NAME['building']);
        foreach ($results as $building) {
            $name = utf8_encode($building[MigrationDb::SITES_6A['name']]);
            $address = utf8_encode($building[MigrationDb::SITES_6A['address']]);
            $newBuilding = (new Building())
                ->setName($name)
                ->setAddress($address)
                ->setCedex($building[MigrationDb::SITES_6A['cedex']])
                ->setDistrib($building[MigrationDb::SITES_6A['distrib']]);
            $communeId = $building[MigrationDb::SITES_6A['rel_commune']];
            $commune = $this->migrationRepository->getCommuneById($communeId);

            if ($commune) {
                $communeName = $commune[MigrationDb::COMMUNES['name']];
                $commune = $this->entityManager->getRepository(Commune::class)
                    ->findOneBy(['Name' => $communeName]);
                $newBuilding->setCommune($commune);
            }
            $this->entityManager->persist($newBuilding);
            $i++;
            if ($i === 100) {
                $this->entityManager->flush();
                $this->entityManager->clear();
                $i = 1;
            }
        }
        $this->entityManager->flush();
        $this->entityManager->clear();

        $output->writeln([
            '===================================================',
            'Migrate ' . count($results) . ' Buildings!',
            '===================================================',
        ]);

        $i = 1;
        $results = $this->migrationRepository->getAll(MigrationDb::TABLE_NAME['site']);
        foreach ($results as $site) {
            $label = utf8_encode($site[MigrationDb::SITES['label']]);
            $newSite = (new Site())->setLabel($label);
            $this->entityManager->persist($newSite);
            $i++;
            if ($i === 100) {
                $this->entityManager->flush();
                $this->entityManager->clear();
                $i = 1;
            }
        }
        $this->entityManager->flush();
        $this->entityManager->clear();

        $output->writeln([
            '===================================================',
            'Migrate ' . count($results) . ' Sites!',
            '===================================================',
        ]);
    }

    private function diagram3(OutputInterface $output)
    {
        //empty the Region database table
        $tables = [
            'movement', 'movement_type', 'correspondent', 'service', 'deposit_type',
            'style', 'era', 'denomination', 'field',
        ];

        $this->dropTables($tables);

        $results = $this->migrationRepository->getAll(MigrationDb::TABLE_NAME['era']);
        foreach ($results as $era) {
            $label = utf8_encode($label = $era[MigrationDb::EPOQUES['label']]);
            $newEra = (new Era())->setLabel($label);
            $this->entityManager->persist($newEra);
        }
        $this->entityManager->flush();
        $this->entityManager->clear();
        $output->writeln([
            '===================================================',
            'Migrate ' . count($results) . ' Eras!',
            '===================================================',
        ]);

        $results = $this->migrationRepository->getAll(MigrationDb::TABLE_NAME['field']);
        foreach ($results as $field) {
            $label = utf8_encode($label = $field[MigrationDb::DOMAINES['label']]);
            $newField = (new Field())->setLabel($label);
            $this->entityManager->persist($newField);
        }
        $this->entityManager->flush();
        $this->entityManager->clear();
        $output->writeln([
            '===================================================',
            'Migrate ' . count($results) . ' Fields!',
            '===================================================',
        ]);

        $results = $this->migrationRepository->getAll(MigrationDb::TABLE_NAME['denomination']);
        foreach ($results as $denomination) {
            $fieldId = $denomination[MigrationDb::DENOMINATIONS['rel_field']];
            $field = $this->migrationRepository->getById(
                MigrationDb::TABLE_NAME['field'],
                MigrationDb::DOMAINES['id'],
                $fieldId
            );
            $fieldName = utf8_encode($field[MigrationDb::DOMAINES['label']]);
            $field = $this->entityManager->getRepository(Field::class)
                ->findOneBy(['label' => $fieldName]);

            $label = utf8_encode($label = $denomination[MigrationDb::DENOMINATIONS['label']]);

            $newField = (new Denomination())->setLabel($label)->setField($field);

            $this->entityManager->persist($newField);
        }
        $this->entityManager->flush();
        $this->entityManager->clear();
        $output->writeln([
            '===================================================',
            'Migrate ' . count($results) . ' Denominations!',
            '===================================================',
        ]);

        $results = $this->migrationRepository->getAll(MigrationDb::TABLE_NAME['style']);
        foreach ($results as $style) {
            $label = utf8_encode($label = $style[MigrationDb::STYLES['label']]);
            $newEntity = (new Style())->setLabel($label);
            $this->entityManager->persist($newEntity);
        }
        $this->entityManager->flush();
        $this->entityManager->clear();
        $output->writeln([
            '===================================================',
            'Migrate ' . count($results) . ' Styles!',
            '===================================================',
        ]);

        $results = $this->migrationRepository->getAll(MigrationDb::TABLE_NAME['deposit_type']);
        foreach ($results as $type) {
            $label = utf8_encode($label = $type[MigrationDb::TYPES_DEPOSANTS['label']]);
            $newEntity = (new DepositType())->setLabel($label);
            $this->entityManager->persist($newEntity);
        }
        $this->entityManager->flush();
        $this->entityManager->clear();
        $output->writeln([
            '===================================================',
            'Migrate ' . count($results) . ' Deposit Types!',
            '===================================================',
        ]);
    }
}
