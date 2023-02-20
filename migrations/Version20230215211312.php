<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230215211312 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE music_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE music (id INT NOT NULL, user_owner_id INT DEFAULT NULL, owner_music_group_id INT DEFAULT NULL, name VARCHAR(100) NOT NULL, url VARCHAR(255) NOT NULL, size VARCHAR(100) DEFAULT NULL, type VARCHAR(10) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_CD52224A9EB185F9 ON music (user_owner_id)');
        $this->addSql('CREATE INDEX IDX_CD52224A7D381DE4 ON music (owner_music_group_id)');
        $this->addSql('ALTER TABLE music ADD CONSTRAINT FK_CD52224A9EB185F9 FOREIGN KEY (user_owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE music ADD CONSTRAINT FK_CD52224A7D381DE4 FOREIGN KEY (owner_music_group_id) REFERENCES music_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE music_id_seq CASCADE');
        $this->addSql('ALTER TABLE music DROP CONSTRAINT FK_CD52224A9EB185F9');
        $this->addSql('ALTER TABLE music DROP CONSTRAINT FK_CD52224A7D381DE4');
        $this->addSql('DROP TABLE music');
    }
}
