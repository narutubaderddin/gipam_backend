<?php

namespace App\Repository;

use Doctrine\DBAL\Connection;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MigrationRepository
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(ContainerInterface $container)
    {
//        $this->connection = $container->get('doctrine.orm.access_entity_manager')->getConnection();
        $connStr = 'odbc:Driver={Microsoft Access Driver (*.mdb, *.accdb)};' .
            'Dbq=C:\\Users\\amchaaya\\Desktop\\Talan\\10-03-2021-MEFI-GIPAM\\dev\\backend\\var\\data\\gipam.mdb;';

        $this->connection = new \PDO($connStr);
        $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function getRegions(): array
    {
        $sql = 'SELECT * FROM regions';

        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();
    }

    public function getRegionById($id)
    {
        $sql = "SELECT * FROM regions WHERE regions.REGION = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function getDepartments(): array
    {
        $sql = 'SELECT * FROM departements';

        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();
    }

    public function getDepartmentById($id)
    {
        $sql = "SELECT * FROM departements WHERE regions.REGION = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
}
