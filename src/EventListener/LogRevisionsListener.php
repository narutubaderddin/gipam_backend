<?php

namespace App\EventListener;



use App\Entity\User;
use App\Services\AuditReader;
use App\Talan\AuditBundle\Annotation\AnnotationLoader;
use App\Talan\AuditBundle\Services\AuditConfiguration;
use App\Talan\AuditBundle\Services\AuditManager;
use App\Talan\AuditBundle\Services\MetadataFactory;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use ReflectionClass;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Security;

class LogRevisionsListener implements EventSubscriber
{
    /**
     * @var AuditConfiguration
     */
    private $config;

    /**
     * @var MetadataFactory
     */
    private $metadataFactory;
    /**
     * @var \Doctrine\DBAL\Connection
     */
    private $conn;

    /**
     * @var \Doctrine\DBAL\Platforms\AbstractPlatform
     */
    private $platform;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @var array
     */
    private $insertRevisionSQL = array();

    /**
     * @var \Doctrine\ORM\UnitOfWork
     */
    private $uow;

    /**
     * @var int
     */
    private $revisionId;
    /**
     * @var AnnotationLoader
     */
    private $annotationLoader;


    public function __construct(AuditManager $auditManager,AnnotationLoader $annotationLoader)
    {
        $this->config = $auditManager->getConfiguration();
        $this->metadataFactory = $auditManager->getMetadataFactory();
        $this->annotationLoader = $annotationLoader;
    }

    public function getSubscribedEvents()
    {
        return array(Events::onFlush, Events::postPersist, Events::postUpdate);
    }

    public function postPersist(LifecycleEventArgs $eventArgs)
    {
        // onFlush was executed before, everything already initialized
        $entity = $eventArgs->getEntity();

        $class = $this->em->getClassMetadata(get_class($entity));
        if (!$this->metadataFactory->isAudited($class->name)) {
            return;
        }
        $action='creation';
        $user='';

        $this->saveRevisionEntityData($class, $this->getOriginalEntityData($entity), 'INS',$user,$action);
    }

    public function postUpdate(LifecycleEventArgs $eventArgs)
    {
        // onFlush was executed before, everything already initialized
        $entity = $eventArgs->getEntity();
        $class = $this->em->getClassMetadata(get_class($entity));
        if (!$this->metadataFactory->isAudited($class->name)) {
            return;
        }

        // get changes => should be already computed here (is a listener)
        $changeset = $this->uow->getEntityChangeSet($entity);
        if(isset($this->config->getGlobalIgnoreColumns()[$class->name])){
            foreach ( $this->config->getGlobalIgnoreColumns()[$class->name] as $column ) {

                if ( isset($changeset[$column]) ) {
                    unset($changeset[$column]);
                }
            }
        }
        // if we have no changes left => don't create revision lognotic
        if ( count($changeset) == 0 ) {
            return;
        }
        $updatedAttibutes = array_diff(array_keys($changeset),['updatedAt']);
        $entityData = array_merge($this->getOriginalEntityData($entity), $this->uow->getEntityIdentifier($entity));
        $action = 'update';
        $actor='';

        $this->saveRevisionEntityData($class, $entityData, 'UPD',$actor,$action,$updatedAttibutes);
    }

    public function onFlush(OnFlushEventArgs $eventArgs)
    {
        $this->em = $eventArgs->getEntityManager();
        $this->conn = $this->em->getConnection();
        $this->uow = $this->em->getUnitOfWork();
        $this->platform = $this->conn->getDatabasePlatform();
        $this->revisionId = null; // reset revision

        foreach ($this->uow->getScheduledEntityDeletions() AS $entity) {
            $class = $this->em->getClassMetadata(get_class($entity));
            if (!$this->metadataFactory->isAudited($class->name)) {
                continue;
            }
            $entityData = array_merge($this->getOriginalEntityData($entity), $this->uow->getEntityIdentifier($entity));
            $actor='';
            $this->saveRevisionEntityData($class, $entityData, 'DEL',$actor,'suppression');
        }
    }

    /**
     * get original entity data, including versioned field, if "version" constraint is used
     *
     * @param mixed $entity
     * @return array
     */
    private function getOriginalEntityData($entity)
    {

        $class = $this->em->getClassMetadata(get_class($entity));
        $data = $this->uow->getOriginalEntityData($entity);
        if( $class->isVersioned ){
            $versionField = $class->versionField;
            $data[$versionField] = $class->reflFields[$versionField]->getValue($entity);
        }
        return $data;
    }

    private function getRevisionId()
    {
        if ($this->revisionId === null) {
            $this->conn->insert($this->config->getRevisionTableName(), array(
                'timestamp'     => date_create('now'),
                'username'      => $this->config->getCurrentUsername(),
            ), array(
                Type::DATETIME,
                Type::STRING
            ));

            $sequenceName = $this->platform->supportsSequences()
                ? 'REVISIONS_ID_SEQ'
                : null;

            $this->revisionId = $this->conn->lastInsertId($sequenceName);
        }
        return $this->revisionId;
    }

    private function getInsertRevisionSQL($class)
    {
        if (!isset($this->insertRevisionSQL[$class->name])) {
            $placeholders = array('?', '?');
            $tableName    = $this->config->getTablePrefix() . $class->table['name'] . $this->config->getTableSuffix();

            $sql = "INSERT INTO " . $tableName . " (" .
                $this->config->getRevisionFieldName() . ", " . $this->config->getRevisionTypeFieldName();

            $fields = array();

            foreach ($class->associationMappings AS $assoc) {
                if ( ($assoc['type'] & ClassMetadataInfo::TO_ONE) > 0 && $assoc['isOwningSide']) {
                    foreach ($assoc['targetToSourceKeyColumns'] as $sourceCol) {
                        $fields[$sourceCol] = true;
                        $sql .= ', ' . $sourceCol;
                        $placeholders[] = '?';
                    }
                }
            }

            foreach ($class->fieldNames AS $field) {
                if (array_key_exists($field, $fields)) {
                    continue;
                }
                $type = Type::getType($class->fieldMappings[$field]['type']);
                $placeholders[] = (!empty($class->fieldMappings[$field]['requireSQLConversion']))
                    ? $type->convertToDatabaseValueSQL('?', $this->platform)
                    : '?';
                $sql .= ', ' . $class->getQuotedColumnName($field, $this->platform);
            }
            $placeholders[]= '?';
            $sql .=', operationDate ';
            $sql .= ") VALUES (" . implode(", ", $placeholders) . ")";
            $this->insertRevisionSQL[$class->name] = $sql;
        }

        return $this->insertRevisionSQL[$class->name];
    }

    /**
     * @param ClassMetadata $class
     * @param array $entityData
     * @param string $revType
     * @throws \Doctrine\DBAL\DBALException
     */
    private function  saveRevisionEntityData($class, $entityData, $revType)
    {
        $params = array($this->getRevisionId(), $revType);
        $types = array(\PDO::PARAM_INT, \PDO::PARAM_STR);
        $fields = array();
        foreach ($class->associationMappings AS $field => $assoc) {
            if (($assoc['type'] & ClassMetadataInfo::TO_ONE) > 0 && $assoc['isOwningSide']) {
                $targetClass = $this->em->getClassMetadata($assoc['targetEntity']);

                if ($entityData[$field] !== null) {
                    $relatedId = $this->uow->getEntityIdentifier($entityData[$field]);
                }

                $targetClass = $this->em->getClassMetadata($assoc['targetEntity']);

                foreach ($assoc['sourceToTargetKeyColumns'] as $sourceColumn => $targetColumn) {
                    $fields[$sourceColumn] = true;
                    if ($entityData[$field] === null) {
                        $params[] = null;
                        $types[] = \PDO::PARAM_STR;
                    } else {
                        $params[] = $relatedId[$targetClass->fieldNames[$targetColumn]];
                        $types[] = $targetClass->getTypeOfColumn($targetColumn);
                    }
                }
            }
        }
        foreach ($class->fieldNames AS $field) {
            if (array_key_exists($field, $fields)) {
                continue;
            }
            if(isset($entityData[$field])){
                $params[] = $entityData[$field];
            }else{
                $params[] = null;
            }
            $types[] = $class->fieldMappings[$field]['type'];
        }
        $params[]= new \DateTime('now');
        $types[] = 'datetime';
        $this->conn->executeUpdate($this->getInsertRevisionSQL($class), $params, $types);
    }

}
