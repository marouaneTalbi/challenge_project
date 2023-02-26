<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230223214903 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE comment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE document_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE music_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE playlist_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE comment (id INT NOT NULL, owner_id INT NOT NULL, message TEXT NOT NULL, created_at DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9474526C7E3C61F9 ON comment (owner_id)');
        $this->addSql('CREATE TABLE document (id INT NOT NULL, music_group_id INT DEFAULT NULL, type VARCHAR(10) NOT NULL, name VARCHAR(255) NOT NULL, size VARCHAR(255) DEFAULT NULL, url VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D8698A7639478561 ON document (music_group_id)');
        $this->addSql('CREATE TABLE music (id INT NOT NULL, user_owner_id INT DEFAULT NULL, owner_music_group_id INT DEFAULT NULL, name VARCHAR(100) NOT NULL, url VARCHAR(255) NOT NULL, size VARCHAR(100) DEFAULT NULL, type VARCHAR(10) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_CD52224A9EB185F9 ON music (user_owner_id)');
        $this->addSql('CREATE INDEX IDX_CD52224A7D381DE4 ON music (owner_music_group_id)');
        $this->addSql('CREATE TABLE playlist (id INT NOT NULL, owner_id INT NOT NULL, name VARCHAR(100) NOT NULL, image VARCHAR(100) DEFAULT NULL, public BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D782112D7E3C61F9 ON playlist (owner_id)');
        $this->addSql('CREATE TABLE playlist_music (playlist_id INT NOT NULL, music_id INT NOT NULL, PRIMARY KEY(playlist_id, music_id))');
        $this->addSql('CREATE INDEX IDX_6E4E3B096BBD148 ON playlist_music (playlist_id)');
        $this->addSql('CREATE INDEX IDX_6E4E3B09399BBB13 ON playlist_music (music_id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C7E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A7639478561 FOREIGN KEY (music_group_id) REFERENCES music_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE music ADD CONSTRAINT FK_CD52224A9EB185F9 FOREIGN KEY (user_owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE music ADD CONSTRAINT FK_CD52224A7D381DE4 FOREIGN KEY (owner_music_group_id) REFERENCES music_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE playlist ADD CONSTRAINT FK_D782112D7E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE playlist_music ADD CONSTRAINT FK_6E4E3B096BBD148 FOREIGN KEY (playlist_id) REFERENCES playlist (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE playlist_music ADD CONSTRAINT FK_6E4E3B09399BBB13 FOREIGN KEY (music_id) REFERENCES music (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE event_user DROP CONSTRAINT fk_92589ae271f7e88b');
        $this->addSql('ALTER TABLE event_user DROP CONSTRAINT fk_92589ae2a76ed395');
        $this->addSql('DROP TABLE event_user');
        $this->addSql('ALTER TABLE "user" ADD image VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE comment_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE document_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE music_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE playlist_id_seq CASCADE');
        $this->addSql('CREATE TABLE event_user (event_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(event_id, user_id))');
        $this->addSql('CREATE INDEX idx_92589ae2a76ed395 ON event_user (user_id)');
        $this->addSql('CREATE INDEX idx_92589ae271f7e88b ON event_user (event_id)');
        $this->addSql('ALTER TABLE event_user ADD CONSTRAINT fk_92589ae271f7e88b FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE event_user ADD CONSTRAINT fk_92589ae2a76ed395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526C7E3C61F9');
        $this->addSql('ALTER TABLE document DROP CONSTRAINT FK_D8698A7639478561');
        $this->addSql('ALTER TABLE music DROP CONSTRAINT FK_CD52224A9EB185F9');
        $this->addSql('ALTER TABLE music DROP CONSTRAINT FK_CD52224A7D381DE4');
        $this->addSql('ALTER TABLE playlist DROP CONSTRAINT FK_D782112D7E3C61F9');
        $this->addSql('ALTER TABLE playlist_music DROP CONSTRAINT FK_6E4E3B096BBD148');
        $this->addSql('ALTER TABLE playlist_music DROP CONSTRAINT FK_6E4E3B09399BBB13');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE document');
        $this->addSql('DROP TABLE music');
        $this->addSql('DROP TABLE playlist');
        $this->addSql('DROP TABLE playlist_music');
        $this->addSql('ALTER TABLE "user" DROP image');
    }
}
