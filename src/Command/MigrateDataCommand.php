<?php

namespace App\Command;

use App\Entity\Departement;
use App\Entity\Region;
use App\Repository\MigrationRepository;
use App\Utilities\MigrationDb;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MigrateDataCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:data:migrate';

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
        $this->setDescription('Load data from Access DB to MySQL DB.')
            ->setHelp('This command allows you to load data from Access DB To MySQL DB.')
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
            'Migrate Data From Access DB to MySQL',
            '====================================',
            '',
        ]);

        $continue = 'yes';
        $continue = $this->io->ask('This commande will delete all the saved data!' .
            'for Region, Department, Commune and Site' .
            ' Are you sure you wish to continue? (yes/no)', 'no');
        $input->setArgument('continue', $continue);
        $continue = strtolower($continue);
        if ($continue === 'no') {
            $output->writeln([
                '',
                '===================================================',
                'Migrate Data From Access DB to MySQL Canceled! Bye!',
            ]);
            return 0;
        }

        //empty the Region database table
        $tables = ['commune', 'departement', 'region', 'site',];
        $connection = $this->entityManager->getConnection();
        $connection->prepare("SET FOREIGN_KEY_CHECKS = 0;")->execute();
        $platform = $connection->getDatabasePlatform();

        foreach ($tables as $table) {
            $truncateSql = $platform->getTruncateTableSQL($table);
            $connection->executeStatement($truncateSql);
        }
        $connection->prepare("SET FOREIGN_KEY_CHECKS = 1;")->execute();

        $regions = $this->migrationRepository->getRegions();
        foreach ($regions as $region) {
            $newRegion = (new Region())->setName($region[MigrationDb::REGIONS['name']]);
            $this->entityManager->persist($newRegion);
        }

        $this->entityManager->flush();

        $departments = $this->migrationRepository->getDepartments();
        foreach ($departments as $department) {
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

        $output->writeln([
            '==================',
            'Migrate Data Done!',
            '',
        ]);

        // return this if there was no problem running the command
        return 0;
    }
}
