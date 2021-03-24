<?php


namespace App\Tests\unit\Entity;


use App\Entity\ArtWork;
use App\Entity\Denomination;
use App\Entity\Domaine;
use App\Entity\Furniture;
use App\Entity\OfficeFurniture;
use Codeception\Test\Unit;
use App\Tests\UnitTester;

class FieldTest extends Unit
{
    /**
     * @var UnitTester
     */
    protected $tester;

    public function testFieldCreation(){
        $field = new Domaine();
        $field->setLabel('Domaine Label');
        $this->assertEquals('Domaine Label',$field->getLabel());
        $denomination = new Denomination();
        $artWork = new ArtWork();
        $officeFurniture = new OfficeFurniture();
        $field->addDenomination($denomination);
        $field->addFurniture($artWork);
        $field->addFurniture($officeFurniture);
        $this->assertContains($denomination,$field->getDenominations());
        $field->removeDenomination($denomination);
        $this->assertNotContains($denomination,$field->getDenominations());
        $this->assertContains($artWork,$field->getFurniture());
        $this->assertContains($officeFurniture,$field->getFurniture());
        $field->removeFurniture($artWork);
        $field->removeFurniture($officeFurniture);
        $this->assertNotContains($artWork,$field->getFurniture());
        $this->assertNotContains($officeFurniture,$field->getFurniture());

    }
}