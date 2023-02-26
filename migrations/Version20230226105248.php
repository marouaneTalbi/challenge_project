<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230226105248 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE news_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE album_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE news_group_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE album (id INT NOT NULL, music_group_id INT NOT NULL, name VARCHAR(50) NOT NULL, image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_39986E4339478561 ON album (music_group_id)');
        $this->addSql('CREATE TABLE news_group (id INT NOT NULL, author_id INT NOT NULL, groupe_id INT DEFAULT NULL, post VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_177FC39BF675F31B ON news_group (author_id)');
        $this->addSql('CREATE INDEX IDX_177FC39B7A45358C ON news_group (groupe_id)');
        $this->addSql('ALTER TABLE album ADD CONSTRAINT FK_39986E4339478561 FOREIGN KEY (music_group_id) REFERENCES music_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE news_group ADD CONSTRAINT FK_177FC39BF675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE news_group ADD CONSTRAINT FK_177FC39B7A45358C FOREIGN KEY (groupe_id) REFERENCES music_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE news DROP CONSTRAINT fk_1dd399509d86650f');
        $this->addSql('ALTER TABLE news DROP CONSTRAINT fk_1dd399508118dc23');
        $this->addSql('DROP TABLE news');
        $this->addSql('ALTER TABLE music ADD album_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE music ADD CONSTRAINT FK_CD52224A1137ABCF FOREIGN KEY (album_id) REFERENCES album (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_CD52224A1137ABCF ON music (album_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE music DROP CONSTRAINT FK_CD52224A1137ABCF');
        $this->addSql('DROP SEQUENCE album_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE news_group_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE news_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE news (id INT NOT NULL, user_id_id INT NOT NULL, music_group_id_id INT NOT NULL, post VARCHAR(255) NOT NULL, status VARCHAR(30) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_1dd399508118dc23 ON news (music_group_id_id)');
        $this->addSql('CREATE INDEX idx_1dd399509d86650f ON news (user_id_id)');
        $this->addSql('ALTER TABLE news ADD CONSTRAINT fk_1dd399509d86650f FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE news ADD CONSTRAINT fk_1dd399508118dc23 FOREIGN KEY (music_group_id_id) REFERENCES music_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE album DROP CONSTRAINT FK_39986E4339478561');
        $this->addSql('ALTER TABLE news_group DROP CONSTRAINT FK_177FC39BF675F31B');
        $this->addSql('ALTER TABLE news_group DROP CONSTRAINT FK_177FC39B7A45358C');
        $this->addSql('DROP TABLE album');
        $this->addSql('DROP TABLE news_group');
        $this->addSql('DROP INDEX IDX_CD52224A1137ABCF');
        $this->addSql('ALTER TABLE music DROP album_id');
    }
}
