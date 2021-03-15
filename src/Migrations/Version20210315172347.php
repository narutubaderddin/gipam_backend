<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210315172347 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE action (id INT AUTO_INCREMENT NOT NULL, type_id INT DEFAULT NULL, movement_action_type_id INT DEFAULT NULL, report_id INT DEFAULT NULL, comment LONGTEXT DEFAULT NULL, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL, period INT NOT NULL, action_nature VARCHAR(255) DEFAULT NULL, INDEX IDX_47CC8C92C54C8C93 (type_id), INDEX IDX_47CC8C92D8686660 (movement_action_type_id), INDEX IDX_47CC8C924BD2A4C0 (report_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE action_movement (action_id INT NOT NULL, movement_id INT NOT NULL, INDEX IDX_230AE5919D32F035 (action_id), INDEX IDX_230AE591229E70A7 (movement_id), PRIMARY KEY(action_id, movement_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE action_type (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE alert (id INT AUTO_INCREMENT NOT NULL, action_id INT DEFAULT NULL, date DATETIME NOT NULL, INDEX IDX_17FD46C19D32F035 (action_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE art_work (id INT NOT NULL, creation_date DATETIME DEFAULT NULL, total_length DOUBLE PRECISION DEFAULT NULL, total_width DOUBLE PRECISION DEFAULT NULL, total_height DOUBLE PRECISION DEFAULT NULL, registration_signature VARCHAR(255) DEFAULT NULL, descriptive_words VARCHAR(255) DEFAULT NULL, insurance_value INT DEFAULT NULL, insurance_value_date DATETIME DEFAULT NULL, deposit_date DATETIME DEFAULT NULL, stop_number VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE art_work_log (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, furniture_id INT DEFAULT NULL, date DATETIME DEFAULT NULL, creation_date DATETIME DEFAULT NULL, total_length DOUBLE PRECISION DEFAULT NULL, total_width DOUBLE PRECISION DEFAULT NULL, total_height DOUBLE PRECISION DEFAULT NULL, registration_signature VARCHAR(255) DEFAULT NULL, descriptive_words VARCHAR(255) DEFAULT NULL, insurance_value INT DEFAULT NULL, insurance_value_date DATETIME DEFAULT NULL, deposit_date DATETIME DEFAULT NULL, stop_number VARCHAR(255) DEFAULT NULL, INDEX IDX_AC56464FA76ED395 (user_id), INDEX IDX_AC56464FCF5485C3 (furniture_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE attachment (id INT AUTO_INCREMENT NOT NULL, furniture_id INT DEFAULT NULL, date DATETIME NOT NULL, comment LONGTEXT DEFAULT NULL, link VARCHAR(255) NOT NULL, principle_image TINYINT(1) NOT NULL, INDEX IDX_795FD9BBCF5485C3 (furniture_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE author (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE author_type (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, label VARCHAR(255) DEFAULT NULL, INDEX IDX_6831DFF6F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE building (id INT AUTO_INCREMENT NOT NULL, site_id INT DEFAULT NULL, commune_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, distrib VARCHAR(255) DEFAULT NULL, start_date DATETIME DEFAULT NULL, disappearance_date DATETIME DEFAULT NULL, INDEX IDX_E16F61D4F6BD1646 (site_id), INDEX IDX_E16F61D4131A4F72 (commune_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commune (id INT AUTO_INCREMENT NOT NULL, departement_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, INDEX IDX_E2E2D1EECCF9E01E (departement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE correspondent (id INT AUTO_INCREMENT NOT NULL, establishment_id INT DEFAULT NULL, sub_division_id INT DEFAULT NULL, service_id INT DEFAULT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, phone VARCHAR(255) DEFAULT NULL, fax VARCHAR(255) DEFAULT NULL, mel VARCHAR(255) DEFAULT NULL, start_date DATETIME DEFAULT NULL, end_date DATETIME DEFAULT NULL, INDEX IDX_E3D4D17F8565851 (establishment_id), INDEX IDX_E3D4D17FA47CE717 (sub_division_id), INDEX IDX_E3D4D17FED5CA9E6 (service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE denomination (id INT AUTO_INCREMENT NOT NULL, field_id INT DEFAULT NULL, label VARCHAR(255) DEFAULT NULL, INDEX IDX_15AEA10C443707B0 (field_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE denomination_material_technique (denomination_id INT NOT NULL, material_technique_id INT NOT NULL, INDEX IDX_FAA0DF54E9293F06 (denomination_id), INDEX IDX_FAA0DF5411F25F26 (material_technique_id), PRIMARY KEY(denomination_id, material_technique_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE departement (id INT AUTO_INCREMENT NOT NULL, region_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, start_date DATETIME DEFAULT NULL, disappearance_date DATETIME DEFAULT NULL, INDEX IDX_C1765B6398260155 (region_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE deposit_status (id INT NOT NULL, depositor_id INT DEFAULT NULL, inventory_number VARCHAR(255) DEFAULT NULL, INDEX IDX_6A8B385DEB8724B4 (depositor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE deposit_type (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE depositor (id INT AUTO_INCREMENT NOT NULL, deposit_type_id INT DEFAULT NULL, acronym VARCHAR(255) DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, dpt VARCHAR(255) DEFAULT NULL, distrib VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, fax VARCHAR(255) DEFAULT NULL, mel VARCHAR(255) DEFAULT NULL, commune VARCHAR(255) DEFAULT NULL, start_date DATETIME DEFAULT NULL, end_date DATETIME DEFAULT NULL, contant VARCHAR(255) DEFAULT NULL, INDEX IDX_9D8D821FC48676C8 (deposit_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entry_mode (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE era (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE establishment (id INT AUTO_INCREMENT NOT NULL, establishment_type_id INT DEFAULT NULL, ministry_id INT DEFAULT NULL, label VARCHAR(255) DEFAULT NULL, acronym VARCHAR(255) DEFAULT NULL, start_date DATETIME DEFAULT NULL, disappearance_date DATETIME DEFAULT NULL, INDEX IDX_DBEFB1EEB86BF9B6 (establishment_type_id), INDEX IDX_DBEFB1EEC7266135 (ministry_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE establishment_type (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE field (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE furniture (id INT AUTO_INCREMENT NOT NULL, era_id INT DEFAULT NULL, type_id INT DEFAULT NULL, style_id INT DEFAULT NULL, material_technique_id INT DEFAULT NULL, denomination_id INT DEFAULT NULL, field_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, length DOUBLE PRECISION DEFAULT NULL, width DOUBLE PRECISION DEFAULT NULL, height DOUBLE PRECISION DEFAULT NULL, depth DOUBLE PRECISION DEFAULT NULL, diameter DOUBLE PRECISION DEFAULT NULL, weight DOUBLE PRECISION DEFAULT NULL, number_of_unit INT DEFAULT NULL, description LONGTEXT DEFAULT NULL, discr VARCHAR(255) NOT NULL, INDEX IDX_665DDAB3707300A1 (era_id), INDEX IDX_665DDAB3C54C8C93 (type_id), INDEX IDX_665DDAB3BACD6074 (style_id), INDEX IDX_665DDAB311F25F26 (material_technique_id), INDEX IDX_665DDAB3E9293F06 (denomination_id), INDEX IDX_665DDAB3443707B0 (field_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE furniture_author (furniture_id INT NOT NULL, author_id INT NOT NULL, INDEX IDX_386AF69ACF5485C3 (furniture_id), INDEX IDX_386AF69AF675F31B (author_id), PRIMARY KEY(furniture_id, author_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE location (id INT AUTO_INCREMENT NOT NULL, location_type_id INT DEFAULT NULL, establishment_id INT DEFAULT NULL, sub_division_id INT DEFAULT NULL, INDEX IDX_5E9E89CB2B099F37 (location_type_id), INDEX IDX_5E9E89CB8565851 (establishment_id), INDEX IDX_5E9E89CBA47CE717 (sub_division_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE location_type (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE material_technique (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ministry (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, acronym VARCHAR(255) DEFAULT NULL, start_date DATETIME DEFAULT NULL, disappearance_date DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE movement (id INT AUTO_INCREMENT NOT NULL, location_id INT DEFAULT NULL, type_id INT DEFAULT NULL, furniture_id INT DEFAULT NULL, date DATETIME NOT NULL, comment LONGTEXT DEFAULT NULL, INDEX IDX_F4DD95F764D218E (location_id), INDEX IDX_F4DD95F7C54C8C93 (type_id), INDEX IDX_F4DD95F7CF5485C3 (furniture_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE movement_correspondent (movement_id INT NOT NULL, correspondent_id INT NOT NULL, INDEX IDX_B21E7704229E70A7 (movement_id), INDEX IDX_B21E77042071082D (correspondent_id), PRIMARY KEY(movement_id, correspondent_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE movement_action_type (id INT AUTO_INCREMENT NOT NULL, movement_type_id INT DEFAULT NULL, label VARCHAR(255) NOT NULL, INDEX IDX_5D22923BEA4ED04A (movement_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE movement_type (id INT AUTO_INCREMENT NOT NULL, label DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE office_furniture (id INT NOT NULL, supplier VARCHAR(255) DEFAULT NULL, buying_price DOUBLE PRECISION DEFAULT NULL, state VARCHAR(255) DEFAULT NULL, unit_volume DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE property_status (id INT NOT NULL, entry_mode_id INT DEFAULT NULL, category_id INT DEFAULT NULL, entry_date DATETIME DEFAULT NULL, marking VARCHAR(255) DEFAULT NULL, property_percentage INT DEFAULT NULL, INDEX IDX_5770A605B032ADD (entry_mode_id), INDEX IDX_5770A6012469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE property_status_category (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE region (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, start_date DATETIME DEFAULT NULL, disappearance_date DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE report (id INT AUTO_INCREMENT NOT NULL, report_sub_type_id INT DEFAULT NULL, furniture_id INT DEFAULT NULL, date DATETIME NOT NULL, comment LONGTEXT DEFAULT NULL, collection_title TINYINT(1) DEFAULT NULL, INDEX IDX_C42F7784B8C7B6F (report_sub_type_id), INDEX IDX_C42F7784CF5485C3 (furniture_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE report_sub_type (id INT AUTO_INCREMENT NOT NULL, report_type_id INT DEFAULT NULL, label VARCHAR(255) DEFAULT NULL, INDEX IDX_AB285195A5D5F193 (report_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE report_type (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE responsible (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, first_name VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, fax VARCHAR(255) DEFAULT NULL, mel VARCHAR(255) DEFAULT NULL, start_date DATETIME DEFAULT NULL, end_date DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE responsible_building (responsible_id INT NOT NULL, building_id INT NOT NULL, INDEX IDX_9FC4DEA3602AD315 (responsible_id), INDEX IDX_9FC4DEA34D2A7E12 (building_id), PRIMARY KEY(responsible_id, building_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE room (id INT AUTO_INCREMENT NOT NULL, building_id INT DEFAULT NULL, reference VARCHAR(255) DEFAULT NULL, level VARCHAR(255) DEFAULT NULL, start_date DATETIME DEFAULT NULL, end_date DATETIME DEFAULT NULL, INDEX IDX_729F519B4D2A7E12 (building_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, sub_division_id INT DEFAULT NULL, label VARCHAR(255) DEFAULT NULL, acronym VARCHAR(255) DEFAULT NULL, start_date DATETIME DEFAULT NULL, disappearance_date DATETIME DEFAULT NULL, INDEX IDX_E19D9AD2A47CE717 (sub_division_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE site (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) DEFAULT NULL, start_date DATETIME DEFAULT NULL, disappearance_date DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE status (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) DEFAULT NULL, start_date DATETIME DEFAULT NULL, end_date DATETIME DEFAULT NULL, comment LONGTEXT DEFAULT NULL, discr VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE style (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sub_division (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) DEFAULT NULL, acronym VARCHAR(255) DEFAULT NULL, start_date DATETIME DEFAULT NULL, end_date DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE action ADD CONSTRAINT FK_47CC8C92C54C8C93 FOREIGN KEY (type_id) REFERENCES action_type (id)');
        $this->addSql('ALTER TABLE action ADD CONSTRAINT FK_47CC8C92D8686660 FOREIGN KEY (movement_action_type_id) REFERENCES movement_action_type (id)');
        $this->addSql('ALTER TABLE action ADD CONSTRAINT FK_47CC8C924BD2A4C0 FOREIGN KEY (report_id) REFERENCES report (id)');
        $this->addSql('ALTER TABLE action_movement ADD CONSTRAINT FK_230AE5919D32F035 FOREIGN KEY (action_id) REFERENCES action (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE action_movement ADD CONSTRAINT FK_230AE591229E70A7 FOREIGN KEY (movement_id) REFERENCES movement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE alert ADD CONSTRAINT FK_17FD46C19D32F035 FOREIGN KEY (action_id) REFERENCES action (id)');
        $this->addSql('ALTER TABLE art_work ADD CONSTRAINT FK_5A6E1E1FBF396750 FOREIGN KEY (id) REFERENCES furniture (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE art_work_log ADD CONSTRAINT FK_AC56464FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE art_work_log ADD CONSTRAINT FK_AC56464FCF5485C3 FOREIGN KEY (furniture_id) REFERENCES furniture (id)');
        $this->addSql('ALTER TABLE attachment ADD CONSTRAINT FK_795FD9BBCF5485C3 FOREIGN KEY (furniture_id) REFERENCES furniture (id)');
        $this->addSql('ALTER TABLE author_type ADD CONSTRAINT FK_6831DFF6F675F31B FOREIGN KEY (author_id) REFERENCES author (id)');
        $this->addSql('ALTER TABLE building ADD CONSTRAINT FK_E16F61D4F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id)');
        $this->addSql('ALTER TABLE building ADD CONSTRAINT FK_E16F61D4131A4F72 FOREIGN KEY (commune_id) REFERENCES commune (id)');
        $this->addSql('ALTER TABLE commune ADD CONSTRAINT FK_E2E2D1EECCF9E01E FOREIGN KEY (departement_id) REFERENCES departement (id)');
        $this->addSql('ALTER TABLE correspondent ADD CONSTRAINT FK_E3D4D17F8565851 FOREIGN KEY (establishment_id) REFERENCES establishment (id)');
        $this->addSql('ALTER TABLE correspondent ADD CONSTRAINT FK_E3D4D17FA47CE717 FOREIGN KEY (sub_division_id) REFERENCES sub_division (id)');
        $this->addSql('ALTER TABLE correspondent ADD CONSTRAINT FK_E3D4D17FED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('ALTER TABLE denomination ADD CONSTRAINT FK_15AEA10C443707B0 FOREIGN KEY (field_id) REFERENCES field (id)');
        $this->addSql('ALTER TABLE denomination_material_technique ADD CONSTRAINT FK_FAA0DF54E9293F06 FOREIGN KEY (denomination_id) REFERENCES denomination (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE denomination_material_technique ADD CONSTRAINT FK_FAA0DF5411F25F26 FOREIGN KEY (material_technique_id) REFERENCES material_technique (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE departement ADD CONSTRAINT FK_C1765B6398260155 FOREIGN KEY (region_id) REFERENCES region (id)');
        $this->addSql('ALTER TABLE deposit_status ADD CONSTRAINT FK_6A8B385DEB8724B4 FOREIGN KEY (depositor_id) REFERENCES depositor (id)');
        $this->addSql('ALTER TABLE deposit_status ADD CONSTRAINT FK_6A8B385DBF396750 FOREIGN KEY (id) REFERENCES status (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE depositor ADD CONSTRAINT FK_9D8D821FC48676C8 FOREIGN KEY (deposit_type_id) REFERENCES deposit_type (id)');
        $this->addSql('ALTER TABLE establishment ADD CONSTRAINT FK_DBEFB1EEB86BF9B6 FOREIGN KEY (establishment_type_id) REFERENCES establishment_type (id)');
        $this->addSql('ALTER TABLE establishment ADD CONSTRAINT FK_DBEFB1EEC7266135 FOREIGN KEY (ministry_id) REFERENCES ministry (id)');
        $this->addSql('ALTER TABLE furniture ADD CONSTRAINT FK_665DDAB3707300A1 FOREIGN KEY (era_id) REFERENCES era (id)');
        $this->addSql('ALTER TABLE furniture ADD CONSTRAINT FK_665DDAB3C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('ALTER TABLE furniture ADD CONSTRAINT FK_665DDAB3BACD6074 FOREIGN KEY (style_id) REFERENCES style (id)');
        $this->addSql('ALTER TABLE furniture ADD CONSTRAINT FK_665DDAB311F25F26 FOREIGN KEY (material_technique_id) REFERENCES material_technique (id)');
        $this->addSql('ALTER TABLE furniture ADD CONSTRAINT FK_665DDAB3E9293F06 FOREIGN KEY (denomination_id) REFERENCES denomination (id)');
        $this->addSql('ALTER TABLE furniture ADD CONSTRAINT FK_665DDAB3443707B0 FOREIGN KEY (field_id) REFERENCES field (id)');
        $this->addSql('ALTER TABLE furniture_author ADD CONSTRAINT FK_386AF69ACF5485C3 FOREIGN KEY (furniture_id) REFERENCES furniture (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE furniture_author ADD CONSTRAINT FK_386AF69AF675F31B FOREIGN KEY (author_id) REFERENCES author (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB2B099F37 FOREIGN KEY (location_type_id) REFERENCES location_type (id)');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB8565851 FOREIGN KEY (establishment_id) REFERENCES establishment (id)');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CBA47CE717 FOREIGN KEY (sub_division_id) REFERENCES sub_division (id)');
        $this->addSql('ALTER TABLE movement ADD CONSTRAINT FK_F4DD95F764D218E FOREIGN KEY (location_id) REFERENCES location (id)');
        $this->addSql('ALTER TABLE movement ADD CONSTRAINT FK_F4DD95F7C54C8C93 FOREIGN KEY (type_id) REFERENCES movement_type (id)');
        $this->addSql('ALTER TABLE movement ADD CONSTRAINT FK_F4DD95F7CF5485C3 FOREIGN KEY (furniture_id) REFERENCES furniture (id)');
        $this->addSql('ALTER TABLE movement_correspondent ADD CONSTRAINT FK_B21E7704229E70A7 FOREIGN KEY (movement_id) REFERENCES movement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE movement_correspondent ADD CONSTRAINT FK_B21E77042071082D FOREIGN KEY (correspondent_id) REFERENCES correspondent (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE movement_action_type ADD CONSTRAINT FK_5D22923BEA4ED04A FOREIGN KEY (movement_type_id) REFERENCES movement_type (id)');
        $this->addSql('ALTER TABLE office_furniture ADD CONSTRAINT FK_70A9B38ABF396750 FOREIGN KEY (id) REFERENCES furniture (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE property_status ADD CONSTRAINT FK_5770A605B032ADD FOREIGN KEY (entry_mode_id) REFERENCES entry_mode (id)');
        $this->addSql('ALTER TABLE property_status ADD CONSTRAINT FK_5770A6012469DE2 FOREIGN KEY (category_id) REFERENCES property_status_category (id)');
        $this->addSql('ALTER TABLE property_status ADD CONSTRAINT FK_5770A60BF396750 FOREIGN KEY (id) REFERENCES status (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F7784B8C7B6F FOREIGN KEY (report_sub_type_id) REFERENCES report_sub_type (id)');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F7784CF5485C3 FOREIGN KEY (furniture_id) REFERENCES furniture (id)');
        $this->addSql('ALTER TABLE report_sub_type ADD CONSTRAINT FK_AB285195A5D5F193 FOREIGN KEY (report_type_id) REFERENCES report_type (id)');
        $this->addSql('ALTER TABLE responsible_building ADD CONSTRAINT FK_9FC4DEA3602AD315 FOREIGN KEY (responsible_id) REFERENCES responsible (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE responsible_building ADD CONSTRAINT FK_9FC4DEA34D2A7E12 FOREIGN KEY (building_id) REFERENCES building (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE room ADD CONSTRAINT FK_729F519B4D2A7E12 FOREIGN KEY (building_id) REFERENCES building (id)');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD2A47CE717 FOREIGN KEY (sub_division_id) REFERENCES sub_division (id)');
        $this->addSql('ALTER TABLE user ADD comment LONGTEXT DEFAULT NULL, ADD start_date DATETIME DEFAULT NULL, ADD end_date DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE action_movement DROP FOREIGN KEY FK_230AE5919D32F035');
        $this->addSql('ALTER TABLE alert DROP FOREIGN KEY FK_17FD46C19D32F035');
        $this->addSql('ALTER TABLE action DROP FOREIGN KEY FK_47CC8C92C54C8C93');
        $this->addSql('ALTER TABLE author_type DROP FOREIGN KEY FK_6831DFF6F675F31B');
        $this->addSql('ALTER TABLE furniture_author DROP FOREIGN KEY FK_386AF69AF675F31B');
        $this->addSql('ALTER TABLE responsible_building DROP FOREIGN KEY FK_9FC4DEA34D2A7E12');
        $this->addSql('ALTER TABLE room DROP FOREIGN KEY FK_729F519B4D2A7E12');
        $this->addSql('ALTER TABLE building DROP FOREIGN KEY FK_E16F61D4131A4F72');
        $this->addSql('ALTER TABLE movement_correspondent DROP FOREIGN KEY FK_B21E77042071082D');
        $this->addSql('ALTER TABLE denomination_material_technique DROP FOREIGN KEY FK_FAA0DF54E9293F06');
        $this->addSql('ALTER TABLE furniture DROP FOREIGN KEY FK_665DDAB3E9293F06');
        $this->addSql('ALTER TABLE commune DROP FOREIGN KEY FK_E2E2D1EECCF9E01E');
        $this->addSql('ALTER TABLE depositor DROP FOREIGN KEY FK_9D8D821FC48676C8');
        $this->addSql('ALTER TABLE deposit_status DROP FOREIGN KEY FK_6A8B385DEB8724B4');
        $this->addSql('ALTER TABLE property_status DROP FOREIGN KEY FK_5770A605B032ADD');
        $this->addSql('ALTER TABLE furniture DROP FOREIGN KEY FK_665DDAB3707300A1');
        $this->addSql('ALTER TABLE correspondent DROP FOREIGN KEY FK_E3D4D17F8565851');
        $this->addSql('ALTER TABLE location DROP FOREIGN KEY FK_5E9E89CB8565851');
        $this->addSql('ALTER TABLE establishment DROP FOREIGN KEY FK_DBEFB1EEB86BF9B6');
        $this->addSql('ALTER TABLE denomination DROP FOREIGN KEY FK_15AEA10C443707B0');
        $this->addSql('ALTER TABLE furniture DROP FOREIGN KEY FK_665DDAB3443707B0');
        $this->addSql('ALTER TABLE art_work DROP FOREIGN KEY FK_5A6E1E1FBF396750');
        $this->addSql('ALTER TABLE art_work_log DROP FOREIGN KEY FK_AC56464FCF5485C3');
        $this->addSql('ALTER TABLE attachment DROP FOREIGN KEY FK_795FD9BBCF5485C3');
        $this->addSql('ALTER TABLE furniture_author DROP FOREIGN KEY FK_386AF69ACF5485C3');
        $this->addSql('ALTER TABLE movement DROP FOREIGN KEY FK_F4DD95F7CF5485C3');
        $this->addSql('ALTER TABLE office_furniture DROP FOREIGN KEY FK_70A9B38ABF396750');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F7784CF5485C3');
        $this->addSql('ALTER TABLE movement DROP FOREIGN KEY FK_F4DD95F764D218E');
        $this->addSql('ALTER TABLE location DROP FOREIGN KEY FK_5E9E89CB2B099F37');
        $this->addSql('ALTER TABLE denomination_material_technique DROP FOREIGN KEY FK_FAA0DF5411F25F26');
        $this->addSql('ALTER TABLE furniture DROP FOREIGN KEY FK_665DDAB311F25F26');
        $this->addSql('ALTER TABLE establishment DROP FOREIGN KEY FK_DBEFB1EEC7266135');
        $this->addSql('ALTER TABLE action_movement DROP FOREIGN KEY FK_230AE591229E70A7');
        $this->addSql('ALTER TABLE movement_correspondent DROP FOREIGN KEY FK_B21E7704229E70A7');
        $this->addSql('ALTER TABLE action DROP FOREIGN KEY FK_47CC8C92D8686660');
        $this->addSql('ALTER TABLE movement DROP FOREIGN KEY FK_F4DD95F7C54C8C93');
        $this->addSql('ALTER TABLE movement_action_type DROP FOREIGN KEY FK_5D22923BEA4ED04A');
        $this->addSql('ALTER TABLE property_status DROP FOREIGN KEY FK_5770A6012469DE2');
        $this->addSql('ALTER TABLE departement DROP FOREIGN KEY FK_C1765B6398260155');
        $this->addSql('ALTER TABLE action DROP FOREIGN KEY FK_47CC8C924BD2A4C0');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F7784B8C7B6F');
        $this->addSql('ALTER TABLE report_sub_type DROP FOREIGN KEY FK_AB285195A5D5F193');
        $this->addSql('ALTER TABLE responsible_building DROP FOREIGN KEY FK_9FC4DEA3602AD315');
        $this->addSql('ALTER TABLE correspondent DROP FOREIGN KEY FK_E3D4D17FED5CA9E6');
        $this->addSql('ALTER TABLE building DROP FOREIGN KEY FK_E16F61D4F6BD1646');
        $this->addSql('ALTER TABLE deposit_status DROP FOREIGN KEY FK_6A8B385DBF396750');
        $this->addSql('ALTER TABLE property_status DROP FOREIGN KEY FK_5770A60BF396750');
        $this->addSql('ALTER TABLE furniture DROP FOREIGN KEY FK_665DDAB3BACD6074');
        $this->addSql('ALTER TABLE correspondent DROP FOREIGN KEY FK_E3D4D17FA47CE717');
        $this->addSql('ALTER TABLE location DROP FOREIGN KEY FK_5E9E89CBA47CE717');
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD2A47CE717');
        $this->addSql('ALTER TABLE furniture DROP FOREIGN KEY FK_665DDAB3C54C8C93');
        $this->addSql('DROP TABLE action');
        $this->addSql('DROP TABLE action_movement');
        $this->addSql('DROP TABLE action_type');
        $this->addSql('DROP TABLE alert');
        $this->addSql('DROP TABLE art_work');
        $this->addSql('DROP TABLE art_work_log');
        $this->addSql('DROP TABLE attachment');
        $this->addSql('DROP TABLE author');
        $this->addSql('DROP TABLE author_type');
        $this->addSql('DROP TABLE building');
        $this->addSql('DROP TABLE commune');
        $this->addSql('DROP TABLE correspondent');
        $this->addSql('DROP TABLE denomination');
        $this->addSql('DROP TABLE denomination_material_technique');
        $this->addSql('DROP TABLE departement');
        $this->addSql('DROP TABLE deposit_status');
        $this->addSql('DROP TABLE deposit_type');
        $this->addSql('DROP TABLE depositor');
        $this->addSql('DROP TABLE entry_mode');
        $this->addSql('DROP TABLE era');
        $this->addSql('DROP TABLE establishment');
        $this->addSql('DROP TABLE establishment_type');
        $this->addSql('DROP TABLE field');
        $this->addSql('DROP TABLE furniture');
        $this->addSql('DROP TABLE furniture_author');
        $this->addSql('DROP TABLE location');
        $this->addSql('DROP TABLE location_type');
        $this->addSql('DROP TABLE material_technique');
        $this->addSql('DROP TABLE ministry');
        $this->addSql('DROP TABLE movement');
        $this->addSql('DROP TABLE movement_correspondent');
        $this->addSql('DROP TABLE movement_action_type');
        $this->addSql('DROP TABLE movement_type');
        $this->addSql('DROP TABLE office_furniture');
        $this->addSql('DROP TABLE property_status');
        $this->addSql('DROP TABLE property_status_category');
        $this->addSql('DROP TABLE region');
        $this->addSql('DROP TABLE report');
        $this->addSql('DROP TABLE report_sub_type');
        $this->addSql('DROP TABLE report_type');
        $this->addSql('DROP TABLE responsible');
        $this->addSql('DROP TABLE responsible_building');
        $this->addSql('DROP TABLE room');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE site');
        $this->addSql('DROP TABLE status');
        $this->addSql('DROP TABLE style');
        $this->addSql('DROP TABLE sub_division');
        $this->addSql('DROP TABLE type');
        $this->addSql('ALTER TABLE user DROP comment, DROP start_date, DROP end_date');
    }
}
