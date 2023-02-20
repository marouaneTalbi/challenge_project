<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230215211758 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE playlist_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE playlist (id INT NOT NULL, owner_id INT NOT NULL, name VARCHAR(100) NOT NULL, image VARCHAR(100) DEFAULT NULL, public BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D782112D7E3C61F9 ON playlist (owner_id)');
        $this->addSql('CREATE TABLE playlist_music (playlist_id INT NOT NULL, music_id INT NOT NULL, PRIMARY KEY(playlist_id, music_id))');
        $this->addSql('CREATE INDEX IDX_6E4E3B096BBD148 ON playlist_music (playlist_id)');
        $this->addSql('CREATE INDEX IDX_6E4E3B09399BBB13 ON playlist_music (music_id)');
        $this->addSql('ALTER TABLE playlist ADD CONSTRAINT FK_D782112D7E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE playlist_music ADD CONSTRAINT FK_6E4E3B096BBD148 FOREIGN KEY (playlist_id) REFERENCES playlist (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE playlist_music ADD CONSTRAINT FK_6E4E3B09399BBB13 FOREIGN KEY (music_id) REFERENCES music (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE playlist_id_seq CASCADE');
        $this->addSql('ALTER TABLE playlist DROP CONSTRAINT FK_D782112D7E3C61F9');
        $this->addSql('ALTER TABLE playlist_music DROP CONSTRAINT FK_6E4E3B096BBD148');
        $this->addSql('ALTER TABLE playlist_music DROP CONSTRAINT FK_6E4E3B09399BBB13');
        $this->addSql('DROP TABLE playlist');
        $this->addSql('DROP TABLE playlist_music');
    }
}
