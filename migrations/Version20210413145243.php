<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210413145243 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
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
        $this->addSql('ALTER TABLE fichier_joint ALTER date DROP NOT NULL');
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
        $this->addSql('ALTER TABLE ministere ADD old_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE etablissement ADD old_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE sous_direction ADD old_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE service ADD old_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE correspondant ADD old_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE domaine ADD old_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE denomination ADD old_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE type_deposant ADD old_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE deposant ADD old_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE region ADD old_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE departement ADD old_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE fichier_joint ALTER date SET NOT NULL');
        $this->addSql('ALTER TABLE commune ADD old_id_1 VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE commune ADD old_id_2 VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE site ADD old_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE batiment ADD old_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE epoque ADD old_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE style ADD old_id VARCHAR(255) DEFAULT NULL');
    }
}
