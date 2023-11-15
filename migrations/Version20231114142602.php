<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231114142602 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE supplement_type ADD COLUMN style VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__supplement_type AS SELECT id, libelle, rating, icon, color FROM supplement_type');
        $this->addSql('DROP TABLE supplement_type');
        $this->addSql('CREATE TABLE supplement_type (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, rating DOUBLE PRECISION NOT NULL, icon VARCHAR(255) DEFAULT NULL, color VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO supplement_type (id, libelle, rating, icon, color) SELECT id, libelle, rating, icon, color FROM __temp__supplement_type');
        $this->addSql('DROP TABLE __temp__supplement_type');
    }
}
