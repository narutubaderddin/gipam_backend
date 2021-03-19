<?php

namespace App\Repository;

use Doctrine\DBAL\Connection;
use PDO;
use Symfony\Component\HttpKernel\KernelInterface;

class MigrationRepository
{

    /**
     * @var Connection
     */
    private $connection;

    public function __construct(KernelInterface $kernel)
    {
//        $this->connection = $container->get('doctrine.orm.access_entity_manager')->getConnection();
        $projectDir = $kernel->getProjectDir();
        $db = str_replace('%kernel.project_dir%', $projectDir, $_ENV['DATABASE_ACCESS_URL']);
        $this->connection = new PDO($db);
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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
        $sql = "SELECT * FROM departements WHERE departements.DEP = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function getDepartmentByRegion($regionId)
    {
        $sql = "SELECT * FROM departements WHERE departements.REGION = :regionId";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['regionId' => $regionId]);
        return $stmt->fetchAll();
    }

    public function getCommune()
    {
        $sql = 'SELECT * FROM communes';
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();
    }

    public function getCommuneByDepartment($departmentId)
    {
        $sql = "SELECT * FROM communes WHERE communes.DEP = :departmentId";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['departmentId' => $departmentId]);
        return $stmt->fetchAll();
    }

    public function getCommuneById($id)
    {
        $sql = "SELECT * FROM communes WHERE communes.COM = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function getBuildings()
    {
        $sql = 'SELECT * FROM sites_6a';
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();
    }

    public function getSites()
    {
        $sql = 'SELECT * FROM sites';
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();
    }

    public function getEras()
    {
        $sql = 'SELECT * FROM epoques';
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();
    }

    public function getById($table, $attribute, $id)
    {
        $sql = "SELECT * FROM " . $table . " WHERE " . $table . '.' . $attribute . " = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function getAll(string $tableName)
    {
        $sql = 'SELECT * FROM ' . $tableName;
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();
    }
}
