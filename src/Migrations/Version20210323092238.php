<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210323092238 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE correspondent DROP old_id');
        $this->addSql('ALTER TABLE correspondent ALTER firstname DROP NOT NULL');
        $this->addSql('ALTER TABLE correspondent ALTER lastname DROP NOT NULL');
        $this->addSql('ALTER TABLE establishment DROP old_id');
        $this->addSql('ALTER TABLE ministry DROP old_id');
        $this->addSql('ALTER TABLE service DROP old_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE Establishment ADD old_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE Correspondent ADD old_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE Correspondent ALTER firstName SET NOT NULL');
        $this->addSql('ALTER TABLE Correspondent ALTER lastName SET NOT NULL');
        $this->addSql('ALTER TABLE Service ADD old_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE Ministry ADD old_id VARCHAR(255) DEFAULT NULL');
    }
}
