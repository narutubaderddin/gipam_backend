<?php

namespace App\Command\Utils;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MigrationRepository
{

    /**
     * @var Connection
     */
    private $oldDBConnection;
    /**
     * @var Connection
     */
    private $newDBConnection;

    public function __construct(EntityManagerInterface $entityManager, ContainerInterface $container)
    {
        $this->oldDBConnection = $container->get('doctrine.orm.old_entity_manager')->getConnection();
        $this->oldDBConnection->getConfiguration()->setSQLLogger(null);
        $this->newDBConnection = $entityManager->getConnection();
        $this->newDBConnection->getConfiguration()->setSQLLogger(null);
    }

    public function getById($table, $attribute, $id)
    {
        $sql = "SELECT * FROM " . $table . " WHERE " . $attribute . " = :id";
        $stmt = $this->oldDBConnection->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function getAll(string $tableName)
    {
        $sql = 'SELECT * FROM ' . $tableName;
        $stmt = $this->oldDBConnection->prepare($sql);
        $stmt->execute();
        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();
    }

    public function insertNewEntity($tableName, array $columns, array $values)
    {
        $i = 1;
        $parameters = '';
        while ($i < count($columns)) {
            $parameters = '?,' . $parameters;
            $i++;
        }
        $parameters = '(SELECT MAX(id)+1 FROM ' . $tableName . '),' . $parameters . '?';
        $columns = 'id, ' . implode(', ', $columns);
        $sql = sprintf("INSERT INTO %s (%s) VALUES (%s)", $tableName, $columns, $parameters);
        $stmt = $this->newDBConnection->prepare($sql);
        $stmt->execute($values);
    }

}
