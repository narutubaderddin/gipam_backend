<?php

namespace App\Talan\AuditBundle\Annotation;


use Doctrine\Common\Annotations\AnnotationReader;
use  Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\EntityManagerInterface;

class AnnotationLoader
{

    /**
     * @var AnnotationReader
     */
    private $reader;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->reader = new AnnotationReader();
    }

    public function load(): array
    {
        $configuration = ['classes' => array(), 'ignored' => array()];

        $metadatas = $this->entityManager->getMetadataFactory()->getAllMetadata();
        foreach ($metadatas as $metadata) {
            if ($this->isAuditable($metadata)) {
                $configuration['classes'][] = $metadata->getName();
                $configuration['ignored'][$metadata->getName()]=$this->getIgnoredFields($metadata);
            }
        }

        return $configuration;
    }

    public function isAuditable(ClassMetadata $metadata): bool
    {
        $reflection = $metadata->getReflectionClass();
        return $this->reader->getClassAnnotation($reflection, Auditable::class) !== null;
    }

    public function getIgnoredFields(ClassMetadata $metadata): array
    {
        $reflection = $metadata->getReflectionClass();
        $properties = [];
        foreach ($reflection->getProperties() as $property) {
            if (null != $this->reader->getPropertyAnnotation($property, Ignore::class)) {
                array_push($properties, $property->getName());
            }
        }
        return $properties;
    }
}
