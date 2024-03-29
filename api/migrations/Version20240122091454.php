<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240122091454 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE person_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE person (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE greeting ADD person_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE greeting ADD CONSTRAINT FK_46E3A4AB217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_46E3A4AB217BBB47 ON greeting (person_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE greeting DROP CONSTRAINT FK_46E3A4AB217BBB47');
        $this->addSql('DROP SEQUENCE person_id_seq CASCADE');
        $this->addSql('DROP TABLE person');
        $this->addSql('DROP INDEX IDX_46E3A4AB217BBB47');
        $this->addSql('ALTER TABLE greeting DROP person_id');
    }
}
