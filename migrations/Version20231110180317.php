<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231110180317 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE score_supplement (score_id INTEGER NOT NULL, supplement_id INTEGER NOT NULL, PRIMARY KEY(score_id, supplement_id), CONSTRAINT FK_F0D0B87112EB0A51 FOREIGN KEY (score_id) REFERENCES score (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_F0D0B8717793FA21 FOREIGN KEY (supplement_id) REFERENCES supplement (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_F0D0B87112EB0A51 ON score_supplement (score_id)');
        $this->addSql('CREATE INDEX IDX_F0D0B8717793FA21 ON score_supplement (supplement_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE score_supplement');
    }
}
