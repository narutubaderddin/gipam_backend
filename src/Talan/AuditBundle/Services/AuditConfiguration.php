<?php

namespace App\Talan\AuditBundle\Services;


use App\Talan\AuditBundle\Annotation\AnnotationLoader;

class AuditConfiguration
{
    private $prefix = '';
    private $suffix = '_audit';
    private $revisionFieldName = 'rev';
    private $revisionTypeFieldName = 'revtype';
    private $revisionTableName = 'revisions';
    private $auditedEntityClasses = array();
    private $globalIgnoreColumns = array();
    private $currentUsername = '';
    private $revisionIdFieldType = 'integer';


    public function __construct($config=[],AnnotationLoader $annotationLoader)
    {
       $data = $annotationLoader->load();
       $this->auditedEntityClasses = $data['classes'];
       $this->globalIgnoreColumns = $data['ignored'];
       $this->initConfiguration($config);
    }

    private function initConfiguration($config){
        $this->prefix = isset($config['table_prefix'])?$config['table_prefix']:$this->prefix;
        $this->suffix = isset($config['table_suffix'])?$config['table_suffix']:$this->suffix;
        $this->revisionFieldName = isset($config['revision_field_name'])?$config['revision_field_name']:$this->revisionFieldName;
        $this->revisionTypeFieldName = isset($config['revision_type_field_name'])?$config['revision_type_field_name']:$this->revisionTypeFieldName;
        $this->revisionTableName = isset($config['revision_table_name'])?$config['revision_table_name']:$this->revisionTableName;
        $this->revisionIdFieldType = isset($config['revision_id_field_type'])?$config['revision_id_field_type']:$this->revisionIdFieldType;
    }
    public function getTablePrefix()
    {
        return $this->prefix;
    }


    public function getTableSuffix()
    {
        return $this->suffix;
    }

    public function getRevisionFieldName()
    {
        return $this->revisionFieldName;
    }


    public function getRevisionTypeFieldName()
    {
        return $this->revisionTypeFieldName;
    }

    public function getRevisionTableName()
    {
        return $this->revisionTableName;
    }

    public function setAuditedEntityClasses(array $classes)
    {
        $this->auditedEntityClasses = $classes;
    }

    public function getGlobalIgnoreColumns()
    {
        return $this->globalIgnoreColumns;
    }

    public function setGlobalIgnoreColumns(array $columns)
    {
        $this->globalIgnoreColumns = $columns;
    }

    public function createMetadataFactory()
    {
        return new MetadataFactory($this->auditedEntityClasses);
    }

    public function setCurrentUsername($username)
    {
        $this->currentUsername = $username;
    }

    public function getCurrentUsername()
    {
        return $this->currentUsername;
    }

    public function getRevisionIdFieldType()
    {
        return $this->revisionIdFieldType;
    }
}