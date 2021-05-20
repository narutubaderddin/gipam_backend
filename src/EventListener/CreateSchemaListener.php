<?php

namespace App\EventListener;

use App\Talan\AuditBundle\Services\AuditConfiguration;
use App\Talan\AuditBundle\Services\AuditManager;
use App\Talan\AuditBundle\Services\MetadataFactory;
use Doctrine\Common\EventSubscriber;
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

    public function __construct(AuditManager $auditManager)
    {
        $this->config = $auditManager->getConfiguration();
        $this->metadataFactory = $auditManager->getMetadataFactory();
    }

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return string[]
     */
    public  function getSubscribedEvents()
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
            $globalIgnoredColumns=$this->config->getGlobalIgnoreColumns();
            $ignoredColumns =  [];
            if(isset($globalIgnoredColumns[$cm->name])){
              $ignoredColumns =  $globalIgnoredColumns[$cm->name];
            }
            foreach ($entityTable->getColumns() AS $column) {
                /* @var $column Column */
                if(false == array_search($column->getName(), $ignoredColumns)){
                    $revisionTable->addColumn($column->getName(), $column->getType()->getName(), array_merge(
                        $column->toArray(),
                        array('notnull' => false, 'autoincrement' => false)
                    ));
                }

            }
            $revisionTable->addColumn($this->config->getRevisionFieldName(), $this->config->getRevisionIdFieldType());
            $revisionTable->addColumn($this->config->getRevisionTypeFieldName(), 'string', array('length' => 4));
            $revisionTable->addColumn('operationDate', 'datetime');
            $revisionTable->addColumn('actor', 'string',array('length' => 200));
            $revisionTable->addColumn('actionType', 'string',array('length' => 200));
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
