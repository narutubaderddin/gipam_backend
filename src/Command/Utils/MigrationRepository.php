<?php

namespace App\Command\Utils;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use PDO;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class MigrationRepository
{

    /**
     * @var Connection|PDO
     */
    public static $oldDBConnection;

    /**
     * @var Connection
     */
    public static $newDBConnection;

    public function __construct(
        KernelInterface $kernel,
        EntityManagerInterface $entityManager,
        ContainerInterface $container
    )
    {
        if (!MigrationDb::USE_ACCESS_DB) {
            self::$oldDBConnection = $container->get('doctrine.orm.old_entity_manager')->getConnection();
            self::$oldDBConnection->getConfiguration()->setSQLLogger(null);
        } else {
            $connStr = str_replace('%kernel.project_dir%', $kernel->getProjectDir(), $_ENV['DATABASE_ACCESS_URL']);
            self::$oldDBConnection = new PDO($connStr);
            self::$oldDBConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        self::$newDBConnection = $entityManager->getConnection();
        self::$newDBConnection->getConfiguration()->setSQLLogger(null);
    }

    public function getByColumn(Connection $connection, $table, $attribute, $id)
    {
        $sql = "SELECT * FROM " . $table . " WHERE " . $attribute . " = :id";
        $stmt = $connection->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetchAll();
    }

    public function getAll($connection, string $tableName)
    {
        $sql = 'SELECT * FROM ' . $tableName;
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();
    }

    /**
     * create an insert sql statement for a table
     * @param $tableName
     * @param array $columns
     * @return string
     */
    public function createInsertStatement($tableName, array $columns): string
    {
        $i = 1;
        $parameters = '';
        while ($i < count($columns)) {
            $parameters = '?,' . $parameters;
            $i++;
        }
        $parameters = "nextval('" . $tableName . "_id_seq')," . $parameters . "?";
        $columns = 'id, ' . implode(', ', $columns);
        return sprintf("INSERT INTO %s (%s) VALUES (%s) RETURNING id", $tableName, $columns, $parameters);
    }

    public function insertNewEntity(string $sql, array $values)
    {
        $stmt = self::$newDBConnection->prepare($sql);
        $stmt->execute($values);
    }

    public function getBy($connection, $tableName, array $criteria)
    {
        $parameters = [];
        foreach ($criteria as $key => $value) {
            $parameters [] = $key . " = :" . $key;
        }
        $sql = "SELECT * FROM " . $tableName . " WHERE ( " . implode(' AND ', $parameters) . ")";
        $stmt = $connection->prepare($sql);
        $stmt->execute($criteria);
        return $stmt->fetchAll();
    }

    public function getOneBy($connection, $tableName, array $criteria)
    {
        $result = $this->getBy($connection, $tableName, $criteria);
        if (count($result)) {
            return $result[0];
        }
        return null;
    }

    public function addOldColumn(array $tables)
    {
        foreach ($tables as $table) {
            // here we add the temporary column
            $columns = MigrationDb::getOldIdColumns($table);
            foreach ($columns as $column) {
                self::$newDBConnection
                    ->executeQuery("ALTER TABLE " . $table . " ADD IF NOT EXISTS " . $column . " VARCHAR");
            }
        }
    }

    public function dropOldColumn(array $tables)
    {
        foreach ($tables as $table) {
            // here we drop the temporary column
            $columns = MigrationDb::getOldIdColumns($table);
            foreach ($columns as $column) {
                self::$newDBConnection->executeQuery("ALTER TABLE " . $table . " DROP COLUMN IF EXISTS " . $column);
            }
        }
    }

    public function dropNewTables(array $tables, $cascade = true)
    {
        foreach ($tables as $table) {
            if ($cascade) {
                self::$newDBConnection->executeQuery("TRUNCATE TABLE " . $table . " RESTART IDENTITY CASCADE");
            } else {
                self::$newDBConnection->executeQuery("TRUNCATE TABLE " . $table . " RESTART IDENTITY");
            }
            self::$newDBConnection->executeQuery("SELECT setval('" . $table . "_id_seq', 1, FALSE)");
        }
    }

    public function update(string $tableName, array $identifierValue, array $columnsValues)
    {
        //UPDATE table_name
        //SET column1=value1, column2=value2,...
        //WHERE some_column=some_value
        $this->prepareValues($columnsValues);
        $keys = array_keys($columnsValues);
        $newValues = '';
        $i = 0;
        while ($i < count($columnsValues) - 1) {
            $newValues = $newValues . $keys[$i] . '=' . $columnsValues[$keys[$i]] . ', ';
            $i++;
        }
        $newValues = $newValues . $keys[$i] . '=' . $columnsValues[$keys[$i]];

        $this->prepareValues($identifierValue);
        $keys = array_keys($identifierValue);
        $identifier = $keys[0] . "=" . $identifierValue[$keys[0]];
        $sql = sprintf("UPDATE %s SET %s WHERE %s", $tableName, $newValues, $identifier);
        $stmt = self::$newDBConnection->prepare($sql);
        $stmt->execute();
    }

    private function prepareValues(array &$columnsValues)
    {
        foreach ($columnsValues as $key => $columnsValue) {
            if (is_string($columnsValue)) {
                $columnsValues[$key] = "'" . $columnsValue . "'";
            }
        }
    }
}
