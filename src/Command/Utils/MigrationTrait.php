<?php

namespace App\Command\Utils;


use Symfony\Component\Console\Output\OutputInterface;

trait MigrationTrait
{
    private function getClass($input, $withNamespace = true)
    {
        $className = '';
        $strings = explode('_', $input);
        foreach ($strings as $string) {
            $className = $className . ucfirst($string);
        }
        if ($withNamespace) {
            return "App\\Entity\\" . $className;
        }
        return $className;
    }

    private function getRelatedTable($relatedTableKey)
    {
        return substr($relatedTableKey, strlen('rel_'));
    }

    private function getRelatedEntity(array $oldEntity, string $relatedClass, bool &$foundError)
    {
        $relatedTableNameMappingTable = MigrationDb::getMappingTable($relatedClass);
        $oldIdColumns = MigrationDb::getOldIdColumns($relatedClass);
        $criteria = [];
        foreach ($oldIdColumns as $oldIdColumn) {
            $criteria[$oldIdColumn] = $oldEntity[$relatedTableNameMappingTable[$oldIdColumn]];
        }
        $relatedEntity = $this->migrationRepository
            ->getBy(MigrationRepository::$newDBConnection, $relatedClass, $criteria);
        if (!empty($relatedEntity)) {
            if (count($relatedEntity) > 1) {
                $foundError = true;
            }
            return $relatedEntity[0]['id'];
        }
        return null;
    }

    private function getAttribute($input)
    {
        if (strpos($input, '_')) {
            $attribute = '';
            $strings = explode('_', $input);
            foreach ($strings as $string) {
                $attribute = $attribute . ucfirst($string);
            }
            return $attribute;
        }
        return ucfirst($input);
    }

    private function printError(bool $foundError, int $groupNumber, OutputInterface $output)
    {
        if ($foundError) {
            $output->writeln('<error> Migration Errors for group ' . $groupNumber .
                ' please see log in : var/log/dev.log </error>');
        }
    }

    private function printWarning(bool $foundWarning, int $groupNumber, OutputInterface $output)
    {
        if ($foundWarning) {
            $output->writeln('<comment> Migration Errors for group ' . $groupNumber .
                ' please see log in : var/log/dev.log </comment>');
        }
    }
}