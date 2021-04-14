<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210413134727 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE log_ouvre_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE log_oeuvre_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE log_oeuvre (id INT NOT NULL, objet_mobilier_id INT DEFAULT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_20047D5CB7105527 ON log_oeuvre (objet_mobilier_id)');
        $this->addSql('ALTER TABLE log_oeuvre ADD CONSTRAINT FK_20047D5CB7105527 FOREIGN KEY (objet_mobilier_id) REFERENCES objet_mobilier (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE log_ouvre');
        $this->addSql('ALTER TABLE batiment DROP old_id');
        $this->addSql('ALTER TABLE commune DROP old_id_1');
        $this->addSql('ALTER TABLE commune DROP old_id_2');
        $this->addSql('ALTER TABLE correspondant DROP old_id');
        $this->addSql('ALTER TABLE denomination DROP old_id');
        $this->addSql('ALTER TABLE departement DROP old_id');
        $this->addSql('ALTER TABLE deposant DROP old_id');
        $this->addSql('ALTER TABLE domaine DROP old_id');
        $this->addSql('ALTER TABLE epoque DROP old_id');
        $this->addSql('ALTER TABLE etablissement DROP old_id');
        $this->addSql('ALTER TABLE ministere DROP old_id');
        $this->addSql('ALTER TABLE region DROP old_id');
        $this->addSql('ALTER TABLE service DROP old_id');
        $this->addSql('ALTER TABLE site DROP old_id');
        $this->addSql('ALTER TABLE sous_direction DROP old_id');
        $this->addSql('ALTER TABLE style DROP old_id');
        $this->addSql('ALTER TABLE type_deposant DROP old_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE log_oeuvre_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE log_ouvre_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE log_ouvre (id INT NOT NULL, objet_mobilier_id INT DEFAULT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_d1723633b7105527 ON log_ouvre (objet_mobilier_id)');
        $this->addSql('ALTER TABLE log_ouvre ADD CONSTRAINT fk_d1723633b7105527 FOREIGN KEY (objet_mobilier_id) REFERENCES objet_mobilier (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE log_oeuvre');
        $this->addSql('ALTER TABLE denomination ADD old_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE domaine ADD old_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE type_deposant ADD old_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE deposant ADD old_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE ministere ADD old_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE etablissement ADD old_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE sous_direction ADD old_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE service ADD old_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE correspondant ADD old_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE commune ADD old_id_1 VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE commune ADD old_id_2 VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE region ADD old_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE departement ADD old_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE site ADD old_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE batiment ADD old_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE style ADD old_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE epoque ADD old_id VARCHAR(255) DEFAULT NULL');
    }
}
