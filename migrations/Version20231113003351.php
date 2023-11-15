<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231113003351 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE answer_supplement (answer_id INTEGER NOT NULL, supplement_id INTEGER NOT NULL, PRIMARY KEY(answer_id, supplement_id), CONSTRAINT FK_6ED7FBF7AA334807 FOREIGN KEY (answer_id) REFERENCES answer (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_6ED7FBF77793FA21 FOREIGN KEY (supplement_id) REFERENCES supplement (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_6ED7FBF7AA334807 ON answer_supplement (answer_id)');
        $this->addSql('CREATE INDEX IDX_6ED7FBF77793FA21 ON answer_supplement (supplement_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE answer_supplement');
    }
}
