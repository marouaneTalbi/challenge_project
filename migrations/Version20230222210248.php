<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230222210248 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE news_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE news_group_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE news_group (id INT NOT NULL, author_id INT NOT NULL, groupe_id INT DEFAULT NULL, post VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_177FC39BF675F31B ON news_group (author_id)');
        $this->addSql('CREATE INDEX IDX_177FC39B7A45358C ON news_group (groupe_id)');
        $this->addSql('ALTER TABLE news_group ADD CONSTRAINT FK_177FC39BF675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE news_group ADD CONSTRAINT FK_177FC39B7A45358C FOREIGN KEY (groupe_id) REFERENCES music_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE news_group_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE news_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('ALTER TABLE news_group DROP CONSTRAINT FK_177FC39BF675F31B');
        $this->addSql('ALTER TABLE news_group DROP CONSTRAINT FK_177FC39B7A45358C');
        $this->addSql('DROP TABLE news_group');
    }
}
