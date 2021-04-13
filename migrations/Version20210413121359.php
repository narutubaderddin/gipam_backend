<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210413121359 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE correspondant DROP old_id');
        $this->addSql('ALTER TABLE correspondant ALTER prenom DROP NOT NULL');
        $this->addSql('ALTER TABLE correspondant ALTER nom DROP NOT NULL');
        $this->addSql('ALTER TABLE etablissement DROP old_id');
        $this->addSql('ALTER TABLE ministere DROP old_id');
        $this->addSql('ALTER TABLE service DROP old_id');
        $this->addSql('ALTER TABLE sous_direction DROP old_id');
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
        $this->addSql('ALTER TABLE correspondant ALTER prenom SET NOT NULL');
        $this->addSql('ALTER TABLE correspondant ALTER nom SET NOT NULL');
    }
}
