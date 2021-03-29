<?php

namespace App\Command;

use App\Command\Utils\MigrationDb;
use App\Command\Utils\MigrationRepository;
use App\Command\Utils\MigrationTrait;
use App\Entity\Auteur;
use App\Entity\Epoque;
use App\Entity\MatiereTechnique;
use App\Entity\ObjetMobilier;
use App\Services\LoggerService;
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

    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:migrate:all';

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

        $command = $this->getApplication()->find('app:migrate');
//        $returnCode = $command->run($input, $output);

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

    private function createFurniture(OutputInterface $output)
    {
        $entity = 'oeuvre_art';
        $this->migrationRepository->dropNewTables(['objet_mobilier']);
        $mappingTable = MigrationDb::getMappingTable($entity);
        $oldEntities = $this->migrationRepository
            ->getAll(MigrationRepository::$oldDBConnection, $mappingTable['table']);
        $foundError = false;
        $rowCount = 1;
        foreach ($oldEntities as $oldEntity) {
            $newEntity = $this->createEntity($entity, $oldEntity, $mappingTable, $foundError);
//            $this->findDimensions($oldEntity, $newEntity);
            $this->findAuthor($oldEntity, $newEntity);
            $this->findMaterialTechnique($oldEntity, $newEntity);
            $this->entityManager->persist($newEntity);
            $rowCount++;
            if ($rowCount % 100) {
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

    private function findDimensions($oldFurniture, ObjetMobilier &$newFurniture)
    {
        $oldEntities = $this->migrationRepository
            ->getBy(MigrationRepository::$oldDBConnection, 'OEUVRES', ['C_MGPAM' => 571]);

        $oldDimensions = strtolower($oldEntities[0]['OE_DIM']);
        $strings = explode('x', $oldDimensions);

        foreach ($strings as $key => $string) {
            $strings[$key] = str_replace('cm', '', $string);
            $strings[$key] = trim($string);
        }
        dd($oldDimensions, $strings);
        if (!$oldDimensions) {
            return;
        }

    }

    private function findAuthor($oldFurniture, ObjetMobilier &$newFurniture)
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

    private function findMaterialTechnique($oldFurniture, ObjetMobilier &$newFurniture)
    {
        $materialTechnique = $oldFurniture[MigrationDb::MATIERE['libelle']];
        $materialTechnique = MigrationDb::utf8Encode($materialTechnique);
        $materialTechnique = $this->entityManager->getRepository(MatiereTechnique::class)
            ->findOneBy(['libelle' => $materialTechnique]);
        if ($materialTechnique) {
            $newFurniture->setMatiereTechnique($materialTechnique);
        }
    }
}
