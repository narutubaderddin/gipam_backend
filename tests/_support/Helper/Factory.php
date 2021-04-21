<?php

namespace App\Tests\Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

use Codeception\Module;

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

    public function _beforeSuite($settings = [])
    {
        /** @var @var DataFactory $factory */
        $this->dataFactory = $this->getModule('DataFactory');
    }

    public function define(string $entityName, array $attribute)
    {
        if ($this->entityName === '') {
            $this->entityName = $entityName;
            $this->dataFactory->_define($this->entityName, $attribute);
        }
    }

    public function have(array $attribute = [])
    {
        if (empty($attribute)) {
            $this->dataFactory->have($this->entityName);
            return;
        }
        $this->dataFactory->have($this->entityName, $attribute);
    }

    public function haveMultiple(int $count, array $attribute = [])
    {
        if (empty($attribute)) {
            $this->dataFactory->haveMultiple($this->entityName, $count);
            return;
        }
        $this->dataFactory->haveMultiple($this->entityName, $count, $attribute);
    }
}
