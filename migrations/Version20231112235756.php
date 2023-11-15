<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231112235756 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__exercise AS SELECT id, title, sub_title, picture FROM exercise');
        $this->addSql('DROP TABLE exercise');
        $this->addSql('CREATE TABLE exercise (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, subject_id INTEGER DEFAULT NULL, title VARCHAR(255) NOT NULL, sub_title CLOB DEFAULT NULL, picture VARCHAR(255) DEFAULT NULL, CONSTRAINT FK_AEDAD51C23EDC87 FOREIGN KEY (subject_id) REFERENCES subject (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO exercise (id, title, sub_title, picture) SELECT id, title, sub_title, picture FROM __temp__exercise');
        $this->addSql('DROP TABLE __temp__exercise');
        $this->addSql('CREATE INDEX IDX_AEDAD51C23EDC87 ON exercise (subject_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__exercise AS SELECT id, title, sub_title, picture FROM exercise');
        $this->addSql('DROP TABLE exercise');
        $this->addSql('CREATE TABLE exercise (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, sub_title CLOB DEFAULT NULL, picture VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO exercise (id, title, sub_title, picture) SELECT id, title, sub_title, picture FROM __temp__exercise');
        $this->addSql('DROP TABLE __temp__exercise');
    }
}
