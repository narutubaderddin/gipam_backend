<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210318115939 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE Action (id INT AUTO_INCREMENT NOT NULL, type_id INT DEFAULT NULL, report_id INT DEFAULT NULL, comment LONGTEXT DEFAULT NULL, startDate DATETIME NOT NULL, endDate DATETIME NOT NULL, period INT NOT NULL, actionNature VARCHAR(255) DEFAULT NULL, movementActionType_id INT DEFAULT NULL, INDEX IDX_406089A4C54C8C93 (type_id), INDEX IDX_406089A4CC710B8A (movementActionType_id), INDEX IDX_406089A44BD2A4C0 (report_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE action_movement (action_id INT NOT NULL, movement_id INT NOT NULL, INDEX IDX_230AE5919D32F035 (action_id), INDEX IDX_230AE591229E70A7 (movement_id), PRIMARY KEY(action_id, movement_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ActionType (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Alert (id INT AUTO_INCREMENT NOT NULL, action_id INT DEFAULT NULL, date DATETIME NOT NULL, INDEX IDX_D63C69C59D32F035 (action_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ArtWork (id INT NOT NULL, creationDate DATETIME DEFAULT NULL, totalLength DOUBLE PRECISION DEFAULT NULL, totalWidth DOUBLE PRECISION DEFAULT NULL, totalHeight DOUBLE PRECISION DEFAULT NULL, registrationSignature VARCHAR(255) DEFAULT NULL, descriptiveWords VARCHAR(255) DEFAULT NULL, insuranceValue INT DEFAULT NULL, insuranceValueDate DATETIME DEFAULT NULL, depositDate DATETIME DEFAULT NULL, stopNumber VARCHAR(255) DEFAULT NULL, otherRegistrations VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ArtWorkLog (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, furniture_id INT DEFAULT NULL, date DATETIME DEFAULT NULL, INDEX IDX_5C6B2EFDA76ED395 (user_id), INDEX IDX_5C6B2EFDCF5485C3 (furniture_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Attachment (id INT AUTO_INCREMENT NOT NULL, furniture_id INT DEFAULT NULL, date DATETIME NOT NULL, comment LONGTEXT DEFAULT NULL, link VARCHAR(255) NOT NULL, principleImage TINYINT(1) NOT NULL, INDEX IDX_3602DA6BCF5485C3 (furniture_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Author (id INT AUTO_INCREMENT NOT NULL, firstName VARCHAR(255) DEFAULT NULL, lastName VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE AuthorType (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, label VARCHAR(255) DEFAULT NULL, INDEX IDX_CF9D36B1F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Building (id INT AUTO_INCREMENT NOT NULL, site_id INT DEFAULT NULL, commune_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, distrib VARCHAR(255) DEFAULT NULL, startDate DATETIME DEFAULT NULL, disappearanceDate DATETIME DEFAULT NULL, cedex VARCHAR(255) DEFAULT NULL, INDEX IDX_18190382F6BD1646 (site_id), INDEX IDX_18190382131A4F72 (commune_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Commune (id INT AUTO_INCREMENT NOT NULL, departement_id INT DEFAULT NULL, Name VARCHAR(255) DEFAULT NULL, INDEX IDX_2D5FE872CCF9E01E (departement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Correspondent (id INT AUTO_INCREMENT NOT NULL, establishment_id INT DEFAULT NULL, service_id INT DEFAULT NULL, firstName VARCHAR(255) NOT NULL, lastName VARCHAR(255) NOT NULL, phone VARCHAR(255) DEFAULT NULL, fax VARCHAR(255) DEFAULT NULL, mel VARCHAR(255) DEFAULT NULL, startDate DATETIME DEFAULT NULL, endDate DATETIME DEFAULT NULL, subDivision_id INT DEFAULT NULL, INDEX IDX_50AA9A8A8565851 (establishment_id), INDEX IDX_50AA9A8A88997EEB (subDivision_id), INDEX IDX_50AA9A8AED5CA9E6 (service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Denomination (id INT AUTO_INCREMENT NOT NULL, field_id INT DEFAULT NULL, label VARCHAR(255) DEFAULT NULL, INDEX IDX_D9F7AF4443707B0 (field_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE denomination_materialtechnique (denomination_id INT NOT NULL, materialtechnique_id INT NOT NULL, INDEX IDX_605345DEE9293F06 (denomination_id), INDEX IDX_605345DEE22B4BD0 (materialtechnique_id), PRIMARY KEY(denomination_id, materialtechnique_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Departement (id INT AUTO_INCREMENT NOT NULL, region_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, startDate DATETIME DEFAULT NULL, disappearanceDate DATETIME DEFAULT NULL, INDEX IDX_47EAD4B498260155 (region_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE DepositStatus (id INT NOT NULL, depositor_id INT DEFAULT NULL, inventoryNumber VARCHAR(255) DEFAULT NULL, INDEX IDX_CFBCDA98EB8724B4 (depositor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE DepositType (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Depositor (id INT AUTO_INCREMENT NOT NULL, acronym VARCHAR(255) DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, dpt VARCHAR(255) DEFAULT NULL, distrib VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, fax VARCHAR(255) DEFAULT NULL, mel VARCHAR(255) DEFAULT NULL, startDate DATETIME DEFAULT NULL, endDate DATETIME DEFAULT NULL, contant VARCHAR(255) DEFAULT NULL, comment LONGTEXT DEFAULT NULL, depositType_id INT DEFAULT NULL, INDEX IDX_1F7C00BCE2490FCD (depositType_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE EntryMode (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Era (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Establishment (id INT AUTO_INCREMENT NOT NULL, ministry_id INT DEFAULT NULL, type_id INT DEFAULT NULL, label VARCHAR(255) DEFAULT NULL, acronym VARCHAR(255) DEFAULT NULL, startDate DATETIME DEFAULT NULL, disappearanceDate DATETIME DEFAULT NULL, INDEX IDX_6891FA1BC7266135 (ministry_id), INDEX IDX_6891FA1BC54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE EstablishmentType (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Field (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Furniture (id INT AUTO_INCREMENT NOT NULL, era_id INT DEFAULT NULL, type_id INT DEFAULT NULL, style_id INT DEFAULT NULL, denomination_id INT DEFAULT NULL, field_id INT DEFAULT NULL, status_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, length DOUBLE PRECISION DEFAULT NULL, width DOUBLE PRECISION DEFAULT NULL, height DOUBLE PRECISION DEFAULT NULL, depth DOUBLE PRECISION DEFAULT NULL, diameter DOUBLE PRECISION DEFAULT NULL, weight DOUBLE PRECISION DEFAULT NULL, numberOfUnit INT DEFAULT NULL, description LONGTEXT DEFAULT NULL, materialTechnique_id INT DEFAULT NULL, discr VARCHAR(255) NOT NULL, INDEX IDX_E4AC5810707300A1 (era_id), INDEX IDX_E4AC5810C54C8C93 (type_id), INDEX IDX_E4AC5810BACD6074 (style_id), INDEX IDX_E4AC5810FA1A9028 (materialTechnique_id), INDEX IDX_E4AC5810E9293F06 (denomination_id), INDEX IDX_E4AC5810443707B0 (field_id), INDEX IDX_E4AC58106BF700BD (status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE furniture_author (furniture_id INT NOT NULL, author_id INT NOT NULL, INDEX IDX_386AF69ACF5485C3 (furniture_id), INDEX IDX_386AF69AF675F31B (author_id), PRIMARY KEY(furniture_id, author_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Location (id INT AUTO_INCREMENT NOT NULL, establishment_id INT DEFAULT NULL, type_id INT DEFAULT NULL, room_id INT DEFAULT NULL, subDivision_id INT DEFAULT NULL, INDEX IDX_A7E8EB9D8565851 (establishment_id), INDEX IDX_A7E8EB9D88997EEB (subDivision_id), INDEX IDX_A7E8EB9DC54C8C93 (type_id), INDEX IDX_A7E8EB9D54177093 (room_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE LocationType (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE MaterialTechnique (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Ministry (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, acronym VARCHAR(255) DEFAULT NULL, startDate DATETIME DEFAULT NULL, disappearanceDate DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Movement (id INT AUTO_INCREMENT NOT NULL, location_id INT DEFAULT NULL, type_id INT DEFAULT NULL, furniture_id INT DEFAULT NULL, date DATETIME NOT NULL, comment LONGTEXT DEFAULT NULL, INDEX IDX_DABF7A164D218E (location_id), INDEX IDX_DABF7A1C54C8C93 (type_id), INDEX IDX_DABF7A1CF5485C3 (furniture_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE movement_correspondent (movement_id INT NOT NULL, correspondent_id INT NOT NULL, INDEX IDX_B21E7704229E70A7 (movement_id), INDEX IDX_B21E77042071082D (correspondent_id), PRIMARY KEY(movement_id, correspondent_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE MovementActionType (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, movementType_id INT DEFAULT NULL, INDEX IDX_31DD29DB9EE20CB0 (movementType_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE MovementType (id INT AUTO_INCREMENT NOT NULL, label DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE OfficeFurniture (id INT NOT NULL, supplier VARCHAR(255) DEFAULT NULL, buyingPrice DOUBLE PRECISION DEFAULT NULL, state VARCHAR(255) DEFAULT NULL, unitVolume DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE PropertyStatus (id INT NOT NULL, category_id INT DEFAULT NULL, entryDate DATETIME DEFAULT NULL, marking VARCHAR(255) DEFAULT NULL, propertyPercentage INT DEFAULT NULL, entryMode_id INT DEFAULT NULL, INDEX IDX_FDE335637E5A47EB (entryMode_id), INDEX IDX_FDE3356312469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE PropertyStatusCategory (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Region (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, startDate DATETIME DEFAULT NULL, disappearanceDate DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Report (id INT AUTO_INCREMENT NOT NULL, furniture_id INT DEFAULT NULL, date DATETIME NOT NULL, comment LONGTEXT DEFAULT NULL, collectionTitle TINYINT(1) DEFAULT NULL, reportSubType_id INT DEFAULT NULL, INDEX IDX_C38372B26DE6D215 (reportSubType_id), INDEX IDX_C38372B2CF5485C3 (furniture_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ReportSubType (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) DEFAULT NULL, reportType_id INT DEFAULT NULL, INDEX IDX_595ACAE9DA318713 (reportType_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ReportType (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Responsible (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, firstName VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, fax VARCHAR(255) DEFAULT NULL, mel VARCHAR(255) DEFAULT NULL, startDate DATETIME DEFAULT NULL, endDate DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE responsible_building (responsible_id INT NOT NULL, building_id INT NOT NULL, INDEX IDX_9FC4DEA3602AD315 (responsible_id), INDEX IDX_9FC4DEA34D2A7E12 (building_id), PRIMARY KEY(responsible_id, building_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Room (id INT AUTO_INCREMENT NOT NULL, building_id INT DEFAULT NULL, reference VARCHAR(255) DEFAULT NULL, level VARCHAR(255) DEFAULT NULL, startDate DATETIME DEFAULT NULL, endDate DATETIME DEFAULT NULL, INDEX IDX_D2ADFEA54D2A7E12 (building_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Service (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) DEFAULT NULL, acronym VARCHAR(255) DEFAULT NULL, startDate DATETIME DEFAULT NULL, disappearanceDate DATETIME DEFAULT NULL, subDivision_id INT DEFAULT NULL, INDEX IDX_2E20A34E88997EEB (subDivision_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Site (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) DEFAULT NULL, startDate DATETIME DEFAULT NULL, disappearanceDate DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Status (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) DEFAULT NULL, startDate DATETIME DEFAULT NULL, endDate DATETIME DEFAULT NULL, comment LONGTEXT DEFAULT NULL, discr VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Style (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE SubDivision (id INT AUTO_INCREMENT NOT NULL, establishment_id INT DEFAULT NULL, label VARCHAR(255) DEFAULT NULL, acronym VARCHAR(255) DEFAULT NULL, startDate DATETIME DEFAULT NULL, endDate DATETIME DEFAULT NULL, INDEX IDX_646D171C8565851 (establishment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Type (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE User (id INT AUTO_INCREMENT NOT NULL, ministry_id INT DEFAULT NULL, username VARCHAR(50) NOT NULL, firstName VARCHAR(50) NOT NULL, lastName VARCHAR(50) NOT NULL, password VARCHAR(100) DEFAULT NULL, roles LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', comment LONGTEXT DEFAULT NULL, startDate DATETIME DEFAULT NULL, endDate DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_2DA17977F85E0677 (username), INDEX IDX_2DA17977C7266135 (ministry_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Action ADD CONSTRAINT FK_406089A4C54C8C93 FOREIGN KEY (type_id) REFERENCES ActionType (id)');
        $this->addSql('ALTER TABLE Action ADD CONSTRAINT FK_406089A4CC710B8A FOREIGN KEY (movementActionType_id) REFERENCES MovementActionType (id)');
        $this->addSql('ALTER TABLE Action ADD CONSTRAINT FK_406089A44BD2A4C0 FOREIGN KEY (report_id) REFERENCES Report (id)');
        $this->addSql('ALTER TABLE action_movement ADD CONSTRAINT FK_230AE5919D32F035 FOREIGN KEY (action_id) REFERENCES Action (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE action_movement ADD CONSTRAINT FK_230AE591229E70A7 FOREIGN KEY (movement_id) REFERENCES Movement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Alert ADD CONSTRAINT FK_D63C69C59D32F035 FOREIGN KEY (action_id) REFERENCES Action (id)');
        $this->addSql('ALTER TABLE ArtWork ADD CONSTRAINT FK_E79053D4BF396750 FOREIGN KEY (id) REFERENCES Furniture (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ArtWorkLog ADD CONSTRAINT FK_5C6B2EFDA76ED395 FOREIGN KEY (user_id) REFERENCES User (id)');
        $this->addSql('ALTER TABLE ArtWorkLog ADD CONSTRAINT FK_5C6B2EFDCF5485C3 FOREIGN KEY (furniture_id) REFERENCES Furniture (id)');
        $this->addSql('ALTER TABLE Attachment ADD CONSTRAINT FK_3602DA6BCF5485C3 FOREIGN KEY (furniture_id) REFERENCES Furniture (id)');
        $this->addSql('ALTER TABLE AuthorType ADD CONSTRAINT FK_CF9D36B1F675F31B FOREIGN KEY (author_id) REFERENCES Author (id)');
        $this->addSql('ALTER TABLE Building ADD CONSTRAINT FK_18190382F6BD1646 FOREIGN KEY (site_id) REFERENCES Site (id)');
        $this->addSql('ALTER TABLE Building ADD CONSTRAINT FK_18190382131A4F72 FOREIGN KEY (commune_id) REFERENCES Commune (id)');
        $this->addSql('ALTER TABLE Commune ADD CONSTRAINT FK_2D5FE872CCF9E01E FOREIGN KEY (departement_id) REFERENCES Departement (id)');
        $this->addSql('ALTER TABLE Correspondent ADD CONSTRAINT FK_50AA9A8A8565851 FOREIGN KEY (establishment_id) REFERENCES Establishment (id)');
        $this->addSql('ALTER TABLE Correspondent ADD CONSTRAINT FK_50AA9A8A88997EEB FOREIGN KEY (subDivision_id) REFERENCES SubDivision (id)');
        $this->addSql('ALTER TABLE Correspondent ADD CONSTRAINT FK_50AA9A8AED5CA9E6 FOREIGN KEY (service_id) REFERENCES Service (id)');
        $this->addSql('ALTER TABLE Denomination ADD CONSTRAINT FK_D9F7AF4443707B0 FOREIGN KEY (field_id) REFERENCES Field (id)');
        $this->addSql('ALTER TABLE denomination_materialtechnique ADD CONSTRAINT FK_605345DEE9293F06 FOREIGN KEY (denomination_id) REFERENCES Denomination (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE denomination_materialtechnique ADD CONSTRAINT FK_605345DEE22B4BD0 FOREIGN KEY (materialtechnique_id) REFERENCES MaterialTechnique (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Departement ADD CONSTRAINT FK_47EAD4B498260155 FOREIGN KEY (region_id) REFERENCES Region (id)');
        $this->addSql('ALTER TABLE DepositStatus ADD CONSTRAINT FK_CFBCDA98EB8724B4 FOREIGN KEY (depositor_id) REFERENCES Depositor (id)');
        $this->addSql('ALTER TABLE DepositStatus ADD CONSTRAINT FK_CFBCDA98BF396750 FOREIGN KEY (id) REFERENCES Status (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Depositor ADD CONSTRAINT FK_1F7C00BCE2490FCD FOREIGN KEY (depositType_id) REFERENCES DepositType (id)');
        $this->addSql('ALTER TABLE Establishment ADD CONSTRAINT FK_6891FA1BC7266135 FOREIGN KEY (ministry_id) REFERENCES Ministry (id)');
        $this->addSql('ALTER TABLE Establishment ADD CONSTRAINT FK_6891FA1BC54C8C93 FOREIGN KEY (type_id) REFERENCES EstablishmentType (id)');
        $this->addSql('ALTER TABLE Furniture ADD CONSTRAINT FK_E4AC5810707300A1 FOREIGN KEY (era_id) REFERENCES Era (id)');
        $this->addSql('ALTER TABLE Furniture ADD CONSTRAINT FK_E4AC5810C54C8C93 FOREIGN KEY (type_id) REFERENCES Type (id)');
        $this->addSql('ALTER TABLE Furniture ADD CONSTRAINT FK_E4AC5810BACD6074 FOREIGN KEY (style_id) REFERENCES Style (id)');
        $this->addSql('ALTER TABLE Furniture ADD CONSTRAINT FK_E4AC5810FA1A9028 FOREIGN KEY (materialTechnique_id) REFERENCES MaterialTechnique (id)');
        $this->addSql('ALTER TABLE Furniture ADD CONSTRAINT FK_E4AC5810E9293F06 FOREIGN KEY (denomination_id) REFERENCES Denomination (id)');
        $this->addSql('ALTER TABLE Furniture ADD CONSTRAINT FK_E4AC5810443707B0 FOREIGN KEY (field_id) REFERENCES Field (id)');
        $this->addSql('ALTER TABLE Furniture ADD CONSTRAINT FK_E4AC58106BF700BD FOREIGN KEY (status_id) REFERENCES Status (id)');
        $this->addSql('ALTER TABLE furniture_author ADD CONSTRAINT FK_386AF69ACF5485C3 FOREIGN KEY (furniture_id) REFERENCES Furniture (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE furniture_author ADD CONSTRAINT FK_386AF69AF675F31B FOREIGN KEY (author_id) REFERENCES Author (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Location ADD CONSTRAINT FK_A7E8EB9D8565851 FOREIGN KEY (establishment_id) REFERENCES Establishment (id)');
        $this->addSql('ALTER TABLE Location ADD CONSTRAINT FK_A7E8EB9D88997EEB FOREIGN KEY (subDivision_id) REFERENCES SubDivision (id)');
        $this->addSql('ALTER TABLE Location ADD CONSTRAINT FK_A7E8EB9DC54C8C93 FOREIGN KEY (type_id) REFERENCES LocationType (id)');
        $this->addSql('ALTER TABLE Location ADD CONSTRAINT FK_A7E8EB9D54177093 FOREIGN KEY (room_id) REFERENCES Room (id)');
        $this->addSql('ALTER TABLE Movement ADD CONSTRAINT FK_DABF7A164D218E FOREIGN KEY (location_id) REFERENCES Location (id)');
        $this->addSql('ALTER TABLE Movement ADD CONSTRAINT FK_DABF7A1C54C8C93 FOREIGN KEY (type_id) REFERENCES MovementType (id)');
        $this->addSql('ALTER TABLE Movement ADD CONSTRAINT FK_DABF7A1CF5485C3 FOREIGN KEY (furniture_id) REFERENCES Furniture (id)');
        $this->addSql('ALTER TABLE movement_correspondent ADD CONSTRAINT FK_B21E7704229E70A7 FOREIGN KEY (movement_id) REFERENCES Movement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE movement_correspondent ADD CONSTRAINT FK_B21E77042071082D FOREIGN KEY (correspondent_id) REFERENCES Correspondent (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE MovementActionType ADD CONSTRAINT FK_31DD29DB9EE20CB0 FOREIGN KEY (movementType_id) REFERENCES MovementType (id)');
        $this->addSql('ALTER TABLE OfficeFurniture ADD CONSTRAINT FK_DA0F5F31BF396750 FOREIGN KEY (id) REFERENCES Furniture (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE PropertyStatus ADD CONSTRAINT FK_FDE335637E5A47EB FOREIGN KEY (entryMode_id) REFERENCES EntryMode (id)');
        $this->addSql('ALTER TABLE PropertyStatus ADD CONSTRAINT FK_FDE3356312469DE2 FOREIGN KEY (category_id) REFERENCES PropertyStatusCategory (id)');
        $this->addSql('ALTER TABLE PropertyStatus ADD CONSTRAINT FK_FDE33563BF396750 FOREIGN KEY (id) REFERENCES Status (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Report ADD CONSTRAINT FK_C38372B26DE6D215 FOREIGN KEY (reportSubType_id) REFERENCES ReportSubType (id)');
        $this->addSql('ALTER TABLE Report ADD CONSTRAINT FK_C38372B2CF5485C3 FOREIGN KEY (furniture_id) REFERENCES Furniture (id)');
        $this->addSql('ALTER TABLE ReportSubType ADD CONSTRAINT FK_595ACAE9DA318713 FOREIGN KEY (reportType_id) REFERENCES ReportType (id)');
        $this->addSql('ALTER TABLE responsible_building ADD CONSTRAINT FK_9FC4DEA3602AD315 FOREIGN KEY (responsible_id) REFERENCES Responsible (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE responsible_building ADD CONSTRAINT FK_9FC4DEA34D2A7E12 FOREIGN KEY (building_id) REFERENCES Building (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Room ADD CONSTRAINT FK_D2ADFEA54D2A7E12 FOREIGN KEY (building_id) REFERENCES Building (id)');
        $this->addSql('ALTER TABLE Service ADD CONSTRAINT FK_2E20A34E88997EEB FOREIGN KEY (subDivision_id) REFERENCES SubDivision (id)');
        $this->addSql('ALTER TABLE SubDivision ADD CONSTRAINT FK_646D171C8565851 FOREIGN KEY (establishment_id) REFERENCES Establishment (id)');
        $this->addSql('ALTER TABLE User ADD CONSTRAINT FK_2DA17977C7266135 FOREIGN KEY (ministry_id) REFERENCES Ministry (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE action_movement DROP FOREIGN KEY FK_230AE5919D32F035');
        $this->addSql('ALTER TABLE Alert DROP FOREIGN KEY FK_D63C69C59D32F035');
        $this->addSql('ALTER TABLE Action DROP FOREIGN KEY FK_406089A4C54C8C93');
        $this->addSql('ALTER TABLE AuthorType DROP FOREIGN KEY FK_CF9D36B1F675F31B');
        $this->addSql('ALTER TABLE furniture_author DROP FOREIGN KEY FK_386AF69AF675F31B');
        $this->addSql('ALTER TABLE responsible_building DROP FOREIGN KEY FK_9FC4DEA34D2A7E12');
        $this->addSql('ALTER TABLE Room DROP FOREIGN KEY FK_D2ADFEA54D2A7E12');
        $this->addSql('ALTER TABLE Building DROP FOREIGN KEY FK_18190382131A4F72');
        $this->addSql('ALTER TABLE movement_correspondent DROP FOREIGN KEY FK_B21E77042071082D');
        $this->addSql('ALTER TABLE denomination_materialtechnique DROP FOREIGN KEY FK_605345DEE9293F06');
        $this->addSql('ALTER TABLE Furniture DROP FOREIGN KEY FK_E4AC5810E9293F06');
        $this->addSql('ALTER TABLE Commune DROP FOREIGN KEY FK_2D5FE872CCF9E01E');
        $this->addSql('ALTER TABLE Depositor DROP FOREIGN KEY FK_1F7C00BCE2490FCD');
        $this->addSql('ALTER TABLE DepositStatus DROP FOREIGN KEY FK_CFBCDA98EB8724B4');
        $this->addSql('ALTER TABLE PropertyStatus DROP FOREIGN KEY FK_FDE335637E5A47EB');
        $this->addSql('ALTER TABLE Furniture DROP FOREIGN KEY FK_E4AC5810707300A1');
        $this->addSql('ALTER TABLE Correspondent DROP FOREIGN KEY FK_50AA9A8A8565851');
        $this->addSql('ALTER TABLE Location DROP FOREIGN KEY FK_A7E8EB9D8565851');
        $this->addSql('ALTER TABLE SubDivision DROP FOREIGN KEY FK_646D171C8565851');
        $this->addSql('ALTER TABLE Establishment DROP FOREIGN KEY FK_6891FA1BC54C8C93');
        $this->addSql('ALTER TABLE Denomination DROP FOREIGN KEY FK_D9F7AF4443707B0');
        $this->addSql('ALTER TABLE Furniture DROP FOREIGN KEY FK_E4AC5810443707B0');
        $this->addSql('ALTER TABLE ArtWork DROP FOREIGN KEY FK_E79053D4BF396750');
        $this->addSql('ALTER TABLE ArtWorkLog DROP FOREIGN KEY FK_5C6B2EFDCF5485C3');
        $this->addSql('ALTER TABLE Attachment DROP FOREIGN KEY FK_3602DA6BCF5485C3');
        $this->addSql('ALTER TABLE furniture_author DROP FOREIGN KEY FK_386AF69ACF5485C3');
        $this->addSql('ALTER TABLE Movement DROP FOREIGN KEY FK_DABF7A1CF5485C3');
        $this->addSql('ALTER TABLE OfficeFurniture DROP FOREIGN KEY FK_DA0F5F31BF396750');
        $this->addSql('ALTER TABLE Report DROP FOREIGN KEY FK_C38372B2CF5485C3');
        $this->addSql('ALTER TABLE Movement DROP FOREIGN KEY FK_DABF7A164D218E');
        $this->addSql('ALTER TABLE Location DROP FOREIGN KEY FK_A7E8EB9DC54C8C93');
        $this->addSql('ALTER TABLE denomination_materialtechnique DROP FOREIGN KEY FK_605345DEE22B4BD0');
        $this->addSql('ALTER TABLE Furniture DROP FOREIGN KEY FK_E4AC5810FA1A9028');
        $this->addSql('ALTER TABLE Establishment DROP FOREIGN KEY FK_6891FA1BC7266135');
        $this->addSql('ALTER TABLE User DROP FOREIGN KEY FK_2DA17977C7266135');
        $this->addSql('ALTER TABLE action_movement DROP FOREIGN KEY FK_230AE591229E70A7');
        $this->addSql('ALTER TABLE movement_correspondent DROP FOREIGN KEY FK_B21E7704229E70A7');
        $this->addSql('ALTER TABLE Action DROP FOREIGN KEY FK_406089A4CC710B8A');
        $this->addSql('ALTER TABLE Movement DROP FOREIGN KEY FK_DABF7A1C54C8C93');
        $this->addSql('ALTER TABLE MovementActionType DROP FOREIGN KEY FK_31DD29DB9EE20CB0');
        $this->addSql('ALTER TABLE PropertyStatus DROP FOREIGN KEY FK_FDE3356312469DE2');
        $this->addSql('ALTER TABLE Departement DROP FOREIGN KEY FK_47EAD4B498260155');
        $this->addSql('ALTER TABLE Action DROP FOREIGN KEY FK_406089A44BD2A4C0');
        $this->addSql('ALTER TABLE Report DROP FOREIGN KEY FK_C38372B26DE6D215');
        $this->addSql('ALTER TABLE ReportSubType DROP FOREIGN KEY FK_595ACAE9DA318713');
        $this->addSql('ALTER TABLE responsible_building DROP FOREIGN KEY FK_9FC4DEA3602AD315');
        $this->addSql('ALTER TABLE Location DROP FOREIGN KEY FK_A7E8EB9D54177093');
        $this->addSql('ALTER TABLE Correspondent DROP FOREIGN KEY FK_50AA9A8AED5CA9E6');
        $this->addSql('ALTER TABLE Building DROP FOREIGN KEY FK_18190382F6BD1646');
        $this->addSql('ALTER TABLE DepositStatus DROP FOREIGN KEY FK_CFBCDA98BF396750');
        $this->addSql('ALTER TABLE Furniture DROP FOREIGN KEY FK_E4AC58106BF700BD');
        $this->addSql('ALTER TABLE PropertyStatus DROP FOREIGN KEY FK_FDE33563BF396750');
        $this->addSql('ALTER TABLE Furniture DROP FOREIGN KEY FK_E4AC5810BACD6074');
        $this->addSql('ALTER TABLE Correspondent DROP FOREIGN KEY FK_50AA9A8A88997EEB');
        $this->addSql('ALTER TABLE Location DROP FOREIGN KEY FK_A7E8EB9D88997EEB');
        $this->addSql('ALTER TABLE Service DROP FOREIGN KEY FK_2E20A34E88997EEB');
        $this->addSql('ALTER TABLE Furniture DROP FOREIGN KEY FK_E4AC5810C54C8C93');
        $this->addSql('ALTER TABLE ArtWorkLog DROP FOREIGN KEY FK_5C6B2EFDA76ED395');
        $this->addSql('DROP TABLE Action');
        $this->addSql('DROP TABLE action_movement');
        $this->addSql('DROP TABLE ActionType');
        $this->addSql('DROP TABLE Alert');
        $this->addSql('DROP TABLE ArtWork');
        $this->addSql('DROP TABLE ArtWorkLog');
        $this->addSql('DROP TABLE Attachment');
        $this->addSql('DROP TABLE Author');
        $this->addSql('DROP TABLE AuthorType');
        $this->addSql('DROP TABLE Building');
        $this->addSql('DROP TABLE Commune');
        $this->addSql('DROP TABLE Correspondent');
        $this->addSql('DROP TABLE Denomination');
        $this->addSql('DROP TABLE denomination_materialtechnique');
        $this->addSql('DROP TABLE Departement');
        $this->addSql('DROP TABLE DepositStatus');
        $this->addSql('DROP TABLE DepositType');
        $this->addSql('DROP TABLE Depositor');
        $this->addSql('DROP TABLE EntryMode');
        $this->addSql('DROP TABLE Era');
        $this->addSql('DROP TABLE Establishment');
        $this->addSql('DROP TABLE EstablishmentType');
        $this->addSql('DROP TABLE Field');
        $this->addSql('DROP TABLE Furniture');
        $this->addSql('DROP TABLE furniture_author');
        $this->addSql('DROP TABLE Location');
        $this->addSql('DROP TABLE LocationType');
        $this->addSql('DROP TABLE MaterialTechnique');
        $this->addSql('DROP TABLE Ministry');
        $this->addSql('DROP TABLE Movement');
        $this->addSql('DROP TABLE movement_correspondent');
        $this->addSql('DROP TABLE MovementActionType');
        $this->addSql('DROP TABLE MovementType');
        $this->addSql('DROP TABLE OfficeFurniture');
        $this->addSql('DROP TABLE PropertyStatus');
        $this->addSql('DROP TABLE PropertyStatusCategory');
        $this->addSql('DROP TABLE Region');
        $this->addSql('DROP TABLE Report');
        $this->addSql('DROP TABLE ReportSubType');
        $this->addSql('DROP TABLE ReportType');
        $this->addSql('DROP TABLE Responsible');
        $this->addSql('DROP TABLE responsible_building');
        $this->addSql('DROP TABLE Room');
        $this->addSql('DROP TABLE Service');
        $this->addSql('DROP TABLE Site');
        $this->addSql('DROP TABLE Status');
        $this->addSql('DROP TABLE Style');
        $this->addSql('DROP TABLE SubDivision');
        $this->addSql('DROP TABLE Type');
        $this->addSql('DROP TABLE User');
    }
}
