<?php
/**
 * Created by PhpStorm.
 * User: schabchoub
 * Date: 15/04/2020
 * Time: 18:41
 */

namespace App\Services;


use App\Talan\AuditBundle\Exception\AuditException;
use App\Talan\AuditBundle\Services\AuditConfiguration;
use App\Talan\AuditBundle\Services\Revision;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\ORM\Query;

class AuditReader
{
    private $em;

    private $config;

    private $metadataFactory;

    /**
     * @param EntityManagerInterface $em
     * @param AuditConfiguration $config
     * @throws DBALException
     */
    public function __construct(EntityManagerInterface $em, AuditConfiguration $config)
    {
        $this->em = $em;
        $this->config = $config;
        $this->metadataFactory = $config->createMetadataFactory();
        $this->platform = $this->em->getConnection()->getDatabasePlatform();
    }

    public function find($className, $id, $revision)
    {
        if (!$this->metadataFactory->isAudited($className)) {
            throw AuditException::notAudited($className);
        }

        $class = $this->em->getClassMetadata($className);
        $tableName = $this->config->getTablePrefix() . $class->table['name'] . $this->config->getTableSuffix();

        if (!is_array($id)) {
            $id = array($class->identifier[0] => $id);
        }

        $whereSQL  = "e." . $this->config->getRevisionFieldName() ." <= ?";

        foreach ($class->identifier AS $idField) {
            if (isset($class->fieldMappings[$idField])) {
                $columnName = $class->fieldMappings[$idField]['columnName'];
            } else if (isset($class->associationMappings[$idField])) {
                $columnName = $class->associationMappings[$idField]['joinColumns'][0];
            }

            $whereSQL .= " AND " . $columnName . " = ?";
        }

        $columnList = "";
        $columnMap  = array();

        foreach ($class->fieldNames as $columnName => $field) {
            if ($columnList) {
                $columnList .= ', ';
            }

            $type = Type::getType($class->fieldMappings[$field]['type']);
            $columnList .= $type->convertToPHPValueSQL(
                    $class->getQuotedColumnName($field, $this->platform), $this->platform) .' AS ' . $field;
            $columnMap[$field] = $this->platform->getSQLResultCasing($columnName);
        }

        foreach ($class->associationMappings AS $assoc) {
            if ( ($assoc['type'] & ClassMetadataInfo::TO_ONE) == 0 || !$assoc['isOwningSide']) {
                continue;
            }

            foreach ($assoc['targetToSourceKeyColumns'] as $sourceCol) {
                if ($columnList) {
                    $columnList .= ', ';
                }

                $columnList .= $sourceCol;
                $columnMap[$sourceCol] = $this->platform->getSQLResultCasing($sourceCol);
            }
        }

        $values = array_merge(array($revision), array_values($id));

        $query = "SELECT " . $columnList . " FROM " . $tableName . " e WHERE " . $whereSQL . " ORDER BY e.rev DESC";

        $row = $this->em->getConnection()->fetchAssoc($query, $values);

        if (!$row) {
            return  $class->newInstance();
        }

        return $this->createEntity($class->name, $row);
    }
    private function createEntity($className, array $data)
    {
        $class = $this->em->getClassMetadata($className);
        $entity = $class->newInstance();

        foreach ($data as $field => $value) {
            if (isset($class->fieldMappings[$field])) {
                $type = Type::getType($class->fieldMappings[$field]['type']);
                $value = $type->convertToPHPValue($value, $this->platform);
                $class->reflFields[$field]->setValue($entity, $value);
            }
        }

        foreach ($class->associationMappings as $field => $assoc) {
            // Check if the association is not among the fetch-joined associations already.
            if (isset($hints['fetched'][$className][$field])) {
                continue;
            }

            $targetClass = $this->em->getClassMetadata($assoc['targetEntity']);

            if ($assoc['type'] & ClassMetadataInfo::TO_ONE) {
                if ($assoc['isOwningSide']) {
                    $associatedId = array();
                    foreach ($assoc['targetToSourceKeyColumns'] as $targetColumn => $srcColumn) {
                        $joinColumnValue = isset($data[$srcColumn]) ? $data[$srcColumn] : null;
                        if ($joinColumnValue !== null) {
                            $associatedId[$targetClass->fieldNames[$targetColumn]] = $joinColumnValue;
                        }
                    }
                    if ( ! $associatedId) {
                        // Foreign key is NULL
                        $class->reflFields[$field]->setValue($entity, null);
                    } else {
                        $associatedEntity = $this->em->getReference($targetClass->name, $associatedId);
                        $class->reflFields[$field]->setValue($entity, $associatedEntity);
                    }
                } else {
                    // Inverse side of x-to-one can never be lazy
                    $class->reflFields[$field]->setValue($entity, $this->getEntityPersister($assoc['targetEntity'])
                        ->loadOneToOneEntity($assoc, $entity));
                }
            } else {
                // Inject collection
                $reflField = $class->reflFields[$field];
                $reflField->setValue($entity, new ArrayCollection);
            }
        }

        return $entity;
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return array
     * @throws DBALException
     * @codeCoverageIgnore
     */
    public function findRevisionHistory($limit = 20, $offset = 0)
    {
        $this->platform = $this->em->getConnection()->getDatabasePlatform();

        $query = $this->platform->modifyLimitQuery(
            "SELECT * FROM " . $this->config->getRevisionTableName() . " ORDER BY id DESC", $limit, $offset
        );
        $revisionsData = $this->em->getConnection()->fetchAll($query);

        $revisions = array();
        foreach ($revisionsData AS $row) {
            $revisions[] = new Revision(
                $row['id'],
                \DateTime::createFromFormat($this->platform->getDateTimeFormatString(), $row['timestamp']),
                $row['username']
            );
        }
        return $revisions;
    }

    /**
     * @param $revision
     * @return array
     * @codeCoverageIgnore
     * @throws DBALException
     */
    public function findEntitesChangedAtRevision($revision)
    {
        return $this->findEntitiesChangedAtRevision($revision);
    }

    /**
     * @param $revision
     * @return array
     * @codeCoverageIgnore
     * @throws DBALException
     */
    public function findEntitiesChangedAtRevision($revision)
    {
        $auditedEntities = $this->metadataFactory->getAllClassNames();

        $changedEntities = array();
        foreach ($auditedEntities AS $className) {
            $class = $this->em->getClassMetadata($className);
            $tableName = $this->config->getTablePrefix() . $class->table['name'] . $this->config->getTableSuffix();

            $whereSQL   = "e." . $this->config->getRevisionFieldName() ." = ?";
            $columnList = "e." . $this->config->getRevisionTypeFieldName();
            $columnMap  = array();

            foreach ($class->fieldNames as $columnName => $field) {
                $type = Type::getType($class->fieldMappings[$field]['type']);
                $columnList .= ', ' . $type->convertToPHPValueSQL(
                        $class->getQuotedColumnName($field, $this->platform), $this->platform) . ' AS ' . $field;
                $columnMap[$field] = $this->platform->getSQLResultCasing($columnName);
            }

            foreach ($class->associationMappings AS $assoc) {
                if ( ($assoc['type'] & ClassMetadataInfo::TO_ONE) > 0 && $assoc['isOwningSide']) {
                    foreach ($assoc['targetToSourceKeyColumns'] as $sourceCol) {
                        $columnList .= ', ' . $sourceCol;
                        $columnMap[$sourceCol] = $this->platform->getSQLResultCasing($sourceCol);
                    }
                }
            }

            $this->platform = $this->em->getConnection()->getDatabasePlatform();
            $query = "SELECT " . $columnList . " FROM " . $tableName . " e WHERE " . $whereSQL;
            $revisionsData = $this->em->getConnection()->executeQuery($query, array($revision));

            foreach ($revisionsData AS $row) {
                $id   = array();
                $data = array();

                foreach ($class->identifier AS $idField) {
                    $id[$idField] = $row[$idField];
                }

                $entity = $this->createEntity($className, $row);
                $changedEntities[] = new ChangedEntity($className, $id, $row[$this->config->getRevisionTypeFieldName()], $entity);
            }
        }
        return $changedEntities;
    }

    /**
     * @param $rev
     * @return Revision
     * @throws AuditException
     * @codeCoverageIgnore
     */
    public function findRevision($rev)
    {
        $query = "SELECT * FROM " . $this->config->getRevisionTableName() . " r WHERE r.id = ?";
        $revisionsData = $this->em->getConnection()->fetchAll($query, array($rev));

        if (count($revisionsData) == 1) {
            return new Revision(
                $revisionsData[0]['id'],
                \DateTime::createFromFormat($this->platform->getDateTimeFormatString(), $revisionsData[0]['timestamp']),
                $revisionsData[0]['username']
            );
        } else {
            throw AuditException::invalidRevision($rev);
        }
    }

    /**
     * @param $className
     * @param $id
     * @return array
     * @throws AuditException
     * @throws DBALException
     * @codeCoverageIgnore
     */
    public function findRevisions($className, $id)
    {
        if (!$this->metadataFactory->isAudited($className)) {
            throw AuditException::notAudited($className);
        }

        $class = $this->em->getClassMetadata($className);
        $tableName = $this->config->getTablePrefix() . $class->table['name'] . $this->config->getTableSuffix();

        if (!is_array($id)) {
            $id = array($class->identifier[0] => $id);
        }

        $whereSQL = "";
        foreach ($class->identifier AS $idField) {
            if (isset($class->fieldMappings[$idField])) {
                if ($whereSQL) {
                    $whereSQL .= " AND ";
                }
                $whereSQL .= "e." . $class->fieldMappings[$idField]['columnName'] . " = ?";
            } else if (isset($class->associationMappings[$idField])) {
                if ($whereSQL) {
                    $whereSQL .= " AND ";
                }
                $whereSQL .= "e." . $class->associationMappings[$idField]['joinColumns'][0] . " = ?";
            }
        }

        $query = "SELECT r.* FROM " . $this->config->getRevisionTableName() . " r " .
            "INNER JOIN " . $tableName . " e ON r.id = e." . $this->config->getRevisionFieldName() . " WHERE " . $whereSQL . " ORDER BY r.id DESC";
        $revisionsData = $this->em->getConnection()->fetchAll($query, array_values($id));

        $revisions = array();
        $this->platform = $this->em->getConnection()->getDatabasePlatform();
        foreach ($revisionsData AS $row) {
            $revisions[] = new Revision(
                $row['id'],
                \DateTime::createFromFormat($this->platform->getDateTimeFormatString(), $row['timestamp']),
                $row['username']
            );
        }

        return $revisions;
    }

    /**
     * @param $className
     * @param $id
     * @return array
     * @throws AuditException
     * @throws DBALException
     * @codeCoverageIgnore
     */
    public function getCurrentRevision($className, $id)
    {
        if (!$this->metadataFactory->isAudited($className)) {
            throw AuditException::notAudited($className);
        }

        $class = $this->em->getClassMetadata($className);
        $tableName = $this->config->getTablePrefix() . $class->table['name'] . $this->config->getTableSuffix();

        if (!is_array($id)) {
            $id = array($class->identifier[0] => $id);
        }

        $whereSQL = "";
        foreach ($class->identifier AS $idField) {
            if (isset($class->fieldMappings[$idField])) {
                if ($whereSQL) {
                    $whereSQL .= " AND ";
                }
                $whereSQL .= "e." . $class->fieldMappings[$idField]['columnName'] . " = ?";
            } else if (isset($class->associationMappings[$idField])) {
                if ($whereSQL) {
                    $whereSQL .= " AND ";
                }
                $whereSQL .= "e." . $class->associationMappings[$idField]['joinColumns'][0] . " = ?";
            }
        }

        $query = "SELECT e.".$this->config->getRevisionFieldName()." FROM " . $tableName . " e " .
            " WHERE " . $whereSQL . " ORDER BY e.".$this->config->getRevisionFieldName()." DESC";
        $revision = $this->em->getConnection()->fetchColumn($query, array_values($id));

        return $revision;
    }

    protected function getEntityPersister($entity)
    {
        $uow = $this->em->getUnitOfWork();
        return $uow->getEntityPersister($entity);
    }
    public function diff($className, $id, $oldRevision, $newRevision)
    {
        $oldObject = $this->find($className, $id, $oldRevision);
        $newObject = $this->find($className, $id, $newRevision);

        $oldValues = $this->getEntityValues($className, $oldObject);
        $newValues = $this->getEntityValues($className, $newObject);

        $differ = new ArrayDiff();
        return $differ->diff($oldValues, $newValues);
    }
    public function getEntityValues($className, $entity)
    {
        $metadata = $this->em->getClassMetadata($className);
        $fields = $metadata->getFieldNames();

        $return = array();
        foreach ($fields AS $fieldName) {
            $return[$fieldName] = $metadata->getFieldValue($entity, $fieldName);
        }

        return $return;
    }

    public function getEntityHistoryCount($className, $id,$search=false){
        if (!$this->metadataFactory->isAudited($className)) {
            throw AuditException::notAudited($className);
        }

        $class = $this->em->getClassMetadata($className);
        $tableName = $this->config->getTablePrefix() . $class->table['name'] . $this->config->getTableSuffix();

        if (!is_array($id)) {
            $id = array($class->identifier[0] => $id);
        }

        $whereId = array();
        foreach ($class->identifier AS $idField) {
            if (isset($class->fieldMappings[$idField])) {
                $columnName = $class->fieldMappings[$idField]['columnName'];
            } else if (isset($class->associationMappings[$idField])) {
                $columnName = $class->associationMappings[$idField]['joinColumns'][0];
            } else {
                continue;
            }

            $whereId[] = "{$columnName} = ?";
        }

        $whereSQL  = implode(' AND ', $whereId);
        $values = array_values($id);
        $query = "SELECT count(*) FROM " . $tableName . " e WHERE " . $whereSQL;
        if($search && strlen($search)>0){
            $query.=' AND  (lower(actor) like ? or lower(actionType) like ?) ';
            $values[]='%'.strtolower($search).'%';
            $values[]='%'.strtolower($search).'%';
        }
        $stmt = $this->em->getConnection()->executeQuery($query, $values);
        return $stmt->fetchColumn();

    }

    public function getEntityHistory($className, $id,$page=1,$pageLength=10,$search=null,$arrayFormat=false)
    {

        $offset = ( $pageLength * $page ) - $pageLength;
        if (!$this->metadataFactory->isAudited($className)) {
            throw AuditException::notAudited($className);
        }

        $class = $this->em->getClassMetadata($className);
        $tableName = $this->config->getTablePrefix() . $class->table['name'] . $this->config->getTableSuffix();

        if (!is_array($id)) {
            $id = array($class->identifier[0] => $id);
        }

        $whereId = array();
        foreach ($class->identifier AS $idField) {
            if (isset($class->fieldMappings[$idField])) {
                $columnName = $class->fieldMappings[$idField]['columnName'];
            } else if (isset($class->associationMappings[$idField])) {
                $columnName = $class->associationMappings[$idField]['joinColumns'][0];
            } else {
                continue;
            }

            $whereId[] = "{$columnName} = ?";
        }

        $whereSQL  = implode(' AND ', $whereId);
        $columnList = "";
        $columnMap  = array();

        foreach ($class->fieldNames as $columnName => $field) {
            if ($columnList) {
                $columnList .= ', ';
            }

            $type = Type::getType($class->fieldMappings[$field]['type']);
            $columnList .= $type->convertToPHPValueSQL(
                    $class->getQuotedColumnName($field, $this->platform), $this->platform) .' AS ' . $field;
            $columnMap[$field] = $this->platform->getSQLResultCasing($columnName);
        }

        foreach ($class->associationMappings AS $assoc) {
            if ( ($assoc['type'] & ClassMetadataInfo::TO_ONE) == 0 || !$assoc['isOwningSide']) {
                continue;
            }

            foreach ($assoc['targetToSourceKeyColumns'] as $sourceCol) {
                if ($columnList) {
                    $columnList .= ', ';
                }

                $columnList .= $sourceCol;
                $columnMap[$sourceCol] = $this->platform->getSQLResultCasing($sourceCol);
            }
        }

        $values = array_values($id);
        if ($columnList) {
            $columnList .= ' , '.$this->config->getRevisionFieldName(). ' as revision , '.
                                 $this->config->getRevisionTypeFieldName() .' as revisionType , operationDate, actor, actionType';
        }

        $query = "SELECT " . $columnList . " FROM " . $tableName . " e WHERE " . $whereSQL ;
        if(strlen($search)>0){
            $query.=' AND  (lower(actor) like ? or lower(actionType) like ?) ';
            $values[]='%'.strtolower($search).'%';
            $values[]='%'.strtolower($search).'%';
        }
        $query.= "  ORDER BY e.rev DESC ";
        $query .= " Limit ".$pageLength. " offset ".$offset;
        $stmt = $this->em->getConnection()->executeQuery($query, $values);

        $result = array();
        while ($row = $stmt->fetch(Query::HYDRATE_ARRAY)) {
            if($arrayFormat){
                $result[] =  $row;
            }else{
                $result[] = $this->createEntity($class->name, $row);
            }
        }
        return $result;
    }
}
