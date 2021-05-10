<?php

namespace App\DataFixtures;

use App\Entity\ArtWork;
use App\Entity\Attachment;
use App\Entity\Author;
use App\Entity\Building;
use App\Entity\Denomination;
use App\Entity\Era;
use App\Entity\Establishment;
use App\Entity\Field;
use App\Entity\Room;
use App\Entity\SubDivision;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $artWork = new ArtWork();
        $artWork->setHeight(99);
        $artWork->setWeight(98);
        $artWork->setDepth(50);
        $artWork->setLength(101);
        $artWork->setTitle('Victoire de Samothrace');
        $artWork->setWidth(100);
        $author = new Author();
        $author->setFirstName('Paul');
        $author->setLastName('Véronèse');
        $manager->persist($author);
        //$artWork->setDescription('une sculpture en marbre dégagée en 1651');
        $artWork->addAuthor($author);
        $field1 = new Field();
        $field1->setLabel('Art textile');
        $field1->setActive(true);
        foreach (["Tapis","Tapisserie","Rideaux"] as $denom){
            $denomenation = new Denomination();
            $denomenation->setLabel($denom);
            $manager->persist($denomenation);
            $field1->addDenomination($denomenation);
            $artWork->setDenomination($denomenation);
        }
        $manager->persist($field1);
        $artWork->setField($field1);
        $attachement  = new Attachment();
        $attachement->setLink('assets/images/365.jpg');
        //$attachement->setPrincipleImage(true);
        $manager->persist($attachement);
        $artWork->addAttachment($attachement);
        $manager->persist($artWork);


        $artWork = new ArtWork();
        $artWork->setHeight(200);
        $artWork->setWeight(201);
        $artWork->setDepth(200);
        //$artWork->setLength(20);
        $artWork->setTitle('HANNIBAL');
        //$artWork->setWidth(200);
        $artWork->setTotalLength(20);
        $author = new Author();
        $author->setFirstName('NECHMI');
        $author->setLastName('Badr');
        $manager->persist($author);
        //$artWork->setDescription('une sculpture en marbre dégagée en 1651');
        $artWork->addAuthor($author);
        $field = new Field();
        $field->setLabel('Art graphique');
        $field->setActive(true);
        $manager->persist($field);
        $artWork->setField($field);
        $attachement  = new Attachment();
        $attachement->setLink('assets/images/35.jpg');
        //$attachement->setPrincipleImage(true);
        $manager->persist($attachement);
        $artWork->addAttachment($attachement);
        $manager->persist($artWork);



        $artWork = new ArtWork();
        $artWork->setHeight(300);
        $artWork->setWeight(300);
        $artWork->setDepth(5);
        $artWork->setLength(300);
        $artWork->setTitle('Les Noces de Cana');
        $artWork->setWidth(300);
        $author = new Author();
        $author->setFirstName('Paul ');
        $author->setLastName('Véronèse');
        $manager->persist($author);
        //$artWork->setDescription('une sculpture en marbre dégagée en 1651');
        $artWork->addAuthor($author);
        $field = new Field();
        $field->setLabel('Horlogerie');
        $field->setActive(true);
        foreach (["Horloge","Pendule","Régulateur"] as $denom){
            $denomenation = new Denomination();
            $denomenation->setLabel($denom);
            $manager->persist($denomenation);
            $field->addDenomination($denomenation);
            $artWork->setDenomination($denomenation);
        }
        $manager->persist($field);
        $artWork->setField($field);
        $attachement  = new Attachment();
        $attachement->setLink('assets/images/304.jpg');
        //$attachement->setPrincipleImage(true);
        $manager->persist($attachement);
        $artWork->addAttachment($attachement);
        $manager->persist($artWork);




        $artWork = new ArtWork();
        $artWork->setHeight(400);
        $artWork->setWeight(400);
        $artWork->setDepth(500);
        //$artWork->setLength(400);
        $artWork->setTitle('Saint Jean-Baptiste');
        $artWork->setWidth(400);
        $artWork->setTotalLength(410);
        $artWork->setTotalWidth(401);
        $artWork->setNumberOfUnit(11);
        $author = new Author();
        $author->setFirstName('Mari ');
        $author->setLastName('lopein');
        $manager->persist($author);
        //$artWork->setDescription('Peint sur une planche de noyer et mesure 69 × 57 cm');
        $artWork->addAuthor($author);
        $era = new Era();
        $manager->persist($era);
        $artWork->setEra($era);
        $field = new Field();
        $field->setLabel('Horlogerie');
        $field->setActive(true);
        foreach (["denom 1","denom 2"] as $denom){
            $denomenation = new Denomination();
            $denomenation->setLabel($denom);
            $manager->persist($denomenation);
            $field->addDenomination($denomenation);
            $artWork->setDenomination($denomenation);
        }
        $manager->persist($field);
        $artWork->setField($field);
        $attachement = new Attachment();
        $attachement->setLink("assets/images/12.jpg");
        //$attachement->setPrincipleImage(true);
        $manager->persist($attachement);
        $artWork->addAttachment($attachement);
        $manager->persist($artWork);


        $artWork = new ArtWork();
        $artWork->setHeight(99);
        $artWork->setWeight(90);
        $artWork->setDepth(50);
        //$artWork->setLength(100);
        $artWork->setTitle('Nature morte');
        //$artWork->setWidth(100);
        $artWork->setTotalLength(100);
        $artWork->setTotalWidth(101);
        $author = new Author();
        $author->setFirstName('Sebastein');
        $author->setLastName('lorein');
        $manager->persist($author);
        //$artWork->setDescription('une sculpture en marbre dégagée en 1651');
        $artWork->addAuthor($author);
        $artWork->setField($field1);
        $attachement  = new Attachment();
        $attachement->setLink('assets/images/365.jpg');
        //$attachement->setPrincipleImage(true);
        $manager->persist($attachement);
        $artWork->addAttachment($attachement);
        $manager->persist($artWork);


        $establishement1 = new Establishment();
        $establishement1->setLabel('Establishement1');
        $establishement1->setAcronym('Acronym1');
        $manager->persist($establishement1);
        $subDivision1 = new SubDivision();
        $subDivision1->setLabel('subDivision1');
        $subDivision1->setEstablishment($establishement1);
        $manager->persist($subDivision1);
        $subDivision2 = new SubDivision();
        $subDivision2->setLabel('subDivision1_1');
        $subDivision2->setEstablishment($establishement1);
        $manager->persist($subDivision2);


        $establishement2 = new Establishment();
        $establishement2->setLabel('Establishement2');
        $establishement2->setAcronym('Acronym2');
        $manager->persist($establishement2);
        $subDivision3 = new SubDivision();
        $subDivision3->setLabel('subDivision2');
        $subDivision3->setEstablishment($establishement2);
        $manager->persist($subDivision3);
        $subDivision4 = new SubDivision();
        $subDivision4->setLabel('subDivision2_2');
        $subDivision4->setEstablishment($establishement2);
        $manager->persist($subDivision4);

        $building1 = new Building();
        $building1->setName('building1');
        $manager->persist($building1);
        $room1= new Room();
        $room1->setLevel('Level1');
        $room1->setBuilding($building1);
        $room1->setReference('ref 1');
        $manager->persist($room1);

        $room1_1= new Room();
        $room1_1->setLevel('Level1');
        $room1_1->setBuilding($building1);
        $room1_1->setReference('ref 1__1');
        $manager->persist($room1_1);

        $building2 = new Building();
        $building2->setName('building2');
        $manager->persist($building2);
        $room2= new Room();
        $room2->setLevel('Level2');
        $room2->setBuilding($building2);
        $room2->setReference('ref 2');
        $manager->persist($room2);

        $manager->flush();
    }
}
