<?php

namespace App\EventListener;

use App\Talan\AuditBundle\Services\AuditConfiguration;
use App\Talan\AuditBundle\Services\AuditManager;
use App\Talan\AuditBundle\Services\MetadataFactory;
use Doctrine\Common\EventSubscriber;
use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Types\IntegerType;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\ORM\Tools\Event\GenerateSchemaEventArgs;
use Doctrine\ORM\Tools\Event\GenerateSchemaTableEventArgs;
use Doctrine\ORM\Tools\ToolEvents;

class CreateSchemaListener implements EventSubscriber
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
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(AuditManager $auditManager, EntityManagerInterface $entityManager)
    {
        $this->config = $auditManager->getConfiguration();
        $this->metadataFactory = $auditManager->getMetadataFactory();
        $this->entityManager = $entityManager;
    }

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return string[]
     */
    public function getSubscribedEvents()
    {
        return array(
            ToolEvents::postGenerateSchemaTable,
            ToolEvents::postGenerateSchema

        );
    }

    public function postGenerateSchemaTable(GenerateSchemaTableEventArgs $eventArgs)
    {
        $cm = $eventArgs->getClassMetadata();
        if ($this->metadataFactory->isAudited($cm->name)) {
            $schema = $eventArgs->getSchema();
            $entityTable = $eventArgs->getClassTable();
            $revisionTable = $schema->createTable(
                $this->config->getTablePrefix() . $entityTable->getName() . $this->config->getTableSuffix()
            );
            $globalIgnoredColumns = $this->config->getGlobalIgnoreColumns();
            $ignoredColumns = [];
            if (isset($globalIgnoredColumns[$cm->name])) {
                $ignoredColumns = $globalIgnoredColumns[$cm->name];
            }
            $columns = [];
            foreach ($entityTable->getColumns() as $column) {
                $columns[] =
                    [
                        'name' => $column->getName(),
                        'type' => $column->getType()->getName(),
                        'data' => $column->toArray()
                    ];
            }
            if ($cm->inheritanceType == ClassMetadataInfo::INHERITANCE_TYPE_JOINED) {
                $parentMetaData = $this->entityManager->getClassMetadata($cm->parentClasses[0]);
                foreach ($parentMetaData->fieldMappings as $field) {
                    if ($field['fieldName'] != 'id') {
                        $fieldData = $field;
                        $fieldData['type']=Type::getType($fieldData['type']);
                        $columns[] = [
                            'name' => $field['columnName'],
                            'type' => $field['type'],
                            'data' => $fieldData
                        ];
                    }
                }
                foreach ($parentMetaData->associationMappings as $assoc) {
                    if (($assoc['type'] & ClassMetadataInfo::TO_ONE) > 0 && $assoc['isOwningSide']) {
                        $assocData = $assoc['joinColumns'][0];
                        $columns[] = [
                            'name' => $assocData['name'],
                            'type' => 'integer',
                            'data' => [
                                "fieldName" => $assocData['name'],
                                "type" =>  Type::getType('integer'),
                                "columnName" => $assocData['name']
                            ]
                        ];
                    }
                }
            } else {
                $columns = $entityTable->getColumns();
            }
            foreach ($columns as $column) {
                if (false == array_search($column['name'], $ignoredColumns)) {
                    $revisionTable->addColumn($column['name'], $column['type'], array_merge(
                        $column['data'],
                        array('notnull' => false, 'autoincrement' => false)
                    ));
                }

            }
            $revisionTable->addColumn($this->config->getRevisionFieldName(), $this->config->getRevisionIdFieldType());
            $revisionTable->addColumn($this->config->getRevisionTypeFieldName(), 'string', array('length' => 4));
            $revisionTable->addColumn('operationDate', 'datetime');
            $revisionTable->addColumn('actor', 'string', array('length' => 200,'notnull' => false));
            $revisionTable->addColumn('actionType', 'string', array('length' => 200,'notnull' => false));
            $revisionTable->addColumn('attribus_modifiee', 'string', array('length' => 200,'notnull' => false));
            $pkColumns = $entityTable->getPrimaryKey()->getColumns();
            $pkColumns[] = $this->config->getRevisionFieldName();
            $revisionTable->setPrimaryKey($pkColumns);
        }
    }

    public function postGenerateSchema(GenerateSchemaEventArgs $eventArgs)
    {
        $schema = $eventArgs->getSchema();
        $revisionsTable = $schema->createTable($this->config->getRevisionTableName());
        $revisionsTable->addColumn('id', $this->config->getRevisionIdFieldType(), array(
            'autoincrement' => true,
        ));
        $revisionsTable->addColumn('timestamp', 'datetime');
        $revisionsTable->addColumn('username', 'string');
        $revisionsTable->setPrimaryKey(array('id'));
    }
}
