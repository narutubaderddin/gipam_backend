<?php

namespace App\Tests\Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

use Codeception\Module;
use Codeception\TestInterface;
use Doctrine\ORM\EntityManager;
use Exception;

class Factory extends Module
{
    /**
     * @var Module\DataFactory
     */
    public $dataFactory;

    /**
     * @var string
     */
    protected $entityName = '';

    /**
     * @var EntityManager
     */
    public $em;

    public function _beforeSuite($settings = [])
    {
        $this->dataFactory = $this->getModule('DataFactory');
        $this->em = $this->getModule('Doctrine2')->_getEntityManager();
    }

    public function define(string $entityName, array $attribute)
    {
        if ($this->entityName === '') {
            $this->entityName = $entityName;
            $this->dataFactory->_define($this->entityName, $attribute);
        }
    }

    /**
     * @param array $attribute
     * @return object
     */
    public function have(array $attribute = [])
    {
        if (empty($attribute)) {
            return $this->dataFactory->have($this->entityName);
        }
        return $this->dataFactory->have($this->entityName, $attribute);
    }

    /**
     * @param int $count
     * @param array $attribute
     * @return object[]
     */
    public function haveMultiple(int $count, array $attribute = [])
    {
        if (empty($attribute)) {
            return $this->dataFactory->haveMultiple($this->entityName, $count);
        }
        return $this->dataFactory->haveMultiple($this->entityName, $count, $attribute);
    }

    public function findEntity(string $entityName, array $attribute)
    {
        if ($this->entityName !== $entityName) {
            $this->dataFactory->_define($entityName, $attribute);
            return $this->dataFactory->have($entityName, $attribute);
        }
        throw new Exception("Entity Name should not be " . $this->entityName);
    }

    public function _after(TestInterface $test)
    {
        $this->em->clear();
        parent::_after($test);
    }
}
