<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230203120154 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE news_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE news (id INT NOT NULL, user_id_id INT NOT NULL, group_id_id INT NOT NULL, post VARCHAR(255) NOT NULL, status VARCHAR(30) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1DD399509D86650F ON news (user_id_id)');
        $this->addSql('CREATE INDEX IDX_1DD399502F68B530 ON news (group_id_id)');
        $this->addSql('ALTER TABLE news ADD CONSTRAINT FK_1DD399509D86650F FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE news ADD CONSTRAINT FK_1DD399502F68B530 FOREIGN KEY (group_id_id) REFERENCES "group" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE news_id_seq CASCADE');
        $this->addSql('ALTER TABLE news DROP CONSTRAINT FK_1DD399509D86650F');
        $this->addSql('ALTER TABLE news DROP CONSTRAINT FK_1DD399502F68B530');
        $this->addSql('DROP TABLE news');
    }
}
