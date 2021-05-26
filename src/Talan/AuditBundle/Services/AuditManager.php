<?php

namespace App\Talan\AuditBundle\Services;


use App\Talan\AuditBundle\EventListener\CreateSchemaListener;
use App\Talan\AuditBundle\EventListener\LogRevisionsListener;
use Doctrine\Common\EventManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class AuditManager
{
    private $config;

    private $metadataFactory;

    /**
     * @param AuditConfiguration $config
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(AuditConfiguration $config, EntityManagerInterface $entityManager)
    {
        $this->config = $config;
        $this->metadataFactory = $config->createMetadataFactory();
    }

    public function getMetadataFactory()
    {
        return $this->metadataFactory;
    }

    public function getConfiguration()
    {
        return $this->config;
    }

    public function createAuditReader(EntityManager $em)
    {
        return new AuditReader($em, $this->config, $this->metadataFactory);
    }

}