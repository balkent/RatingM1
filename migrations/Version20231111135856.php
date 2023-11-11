<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231111135856 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE supplement ADD COLUMN rating DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__supplement AS SELECT id, type_id, libelle FROM supplement');
        $this->addSql('DROP TABLE supplement');
        $this->addSql('CREATE TABLE supplement (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, type_id INTEGER NOT NULL, libelle VARCHAR(255) NOT NULL, CONSTRAINT FK_15A73C9C54C8C93 FOREIGN KEY (type_id) REFERENCES supplement_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO supplement (id, type_id, libelle) SELECT id, type_id, libelle FROM __temp__supplement');
        $this->addSql('DROP TABLE __temp__supplement');
        $this->addSql('CREATE INDEX IDX_15A73C9C54C8C93 ON supplement (type_id)');
    }
}
