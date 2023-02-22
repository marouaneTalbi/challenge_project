<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230220164239 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE news DROP CONSTRAINT fk_1dd399502f68b530');
        $this->addSql('ALTER TABLE subscription DROP CONSTRAINT fk_a3c664d32f68b530');
        $this->addSql('DROP SEQUENCE group_id_seq CASCADE');
        $this->addSql('CREATE TABLE event_user (event_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(event_id, user_id))');
        $this->addSql('CREATE INDEX IDX_92589AE271F7E88B ON event_user (event_id)');
        $this->addSql('CREATE INDEX IDX_92589AE2A76ED395 ON event_user (user_id)');
        $this->addSql('ALTER TABLE event_user ADD CONSTRAINT FK_92589AE271F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE event_user ADD CONSTRAINT FK_92589AE2A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE "group"');
        $this->addSql('ALTER TABLE event ADD music_group_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE event ADD title VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE event ADD description TEXT NOT NULL');
        $this->addSql('ALTER TABLE event ADD lieu VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE event ADD public BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE event ADD event_end TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE event ADD background_color VARCHAR(7) NOT NULL');
        $this->addSql('ALTER TABLE event ADD border_color VARCHAR(7) NOT NULL');
        $this->addSql('ALTER TABLE event ADD text_color VARCHAR(7) NOT NULL');
        $this->addSql('ALTER TABLE event DROP name');
        $this->addSql('ALTER TABLE event DROP address');
        $this->addSql('ALTER TABLE event DROP status');
        $this->addSql('ALTER TABLE event RENAME COLUMN date TO event_start');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA739478561 FOREIGN KEY (music_group_id) REFERENCES music_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_3BAE0AA739478561 ON event (music_group_id)');
        $this->addSql('DROP INDEX idx_1dd399502f68b530');
        $this->addSql('ALTER TABLE news RENAME COLUMN group_id_id TO music_group_id_id');
        $this->addSql('ALTER TABLE news ADD CONSTRAINT FK_1DD399508118DC23 FOREIGN KEY (music_group_id_id) REFERENCES music_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_1DD399508118DC23 ON news (music_group_id_id)');
        $this->addSql('DROP INDEX idx_a3c664d32f68b530');
        $this->addSql('ALTER TABLE subscription RENAME COLUMN group_id_id TO music_group_id_id');
        $this->addSql('ALTER TABLE subscription ADD CONSTRAINT FK_A3C664D38118DC23 FOREIGN KEY (music_group_id_id) REFERENCES music_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_A3C664D38118DC23 ON subscription (music_group_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE group_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "group" (id INT NOT NULL, name VARCHAR(30) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE event_user DROP CONSTRAINT FK_92589AE271F7E88B');
        $this->addSql('ALTER TABLE event_user DROP CONSTRAINT FK_92589AE2A76ED395');
        $this->addSql('DROP TABLE event_user');
        $this->addSql('ALTER TABLE news DROP CONSTRAINT FK_1DD399508118DC23');
        $this->addSql('DROP INDEX IDX_1DD399508118DC23');
        $this->addSql('ALTER TABLE news RENAME COLUMN music_group_id_id TO group_id_id');
        $this->addSql('ALTER TABLE news ADD CONSTRAINT fk_1dd399502f68b530 FOREIGN KEY (group_id_id) REFERENCES "group" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_1dd399502f68b530 ON news (group_id_id)');
        $this->addSql('ALTER TABLE subscription DROP CONSTRAINT FK_A3C664D38118DC23');
        $this->addSql('DROP INDEX IDX_A3C664D38118DC23');
        $this->addSql('ALTER TABLE subscription RENAME COLUMN music_group_id_id TO group_id_id');
        $this->addSql('ALTER TABLE subscription ADD CONSTRAINT fk_a3c664d32f68b530 FOREIGN KEY (group_id_id) REFERENCES "group" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_a3c664d32f68b530 ON subscription (group_id_id)');
        $this->addSql('ALTER TABLE event DROP CONSTRAINT FK_3BAE0AA739478561');
        $this->addSql('DROP INDEX IDX_3BAE0AA739478561');
        $this->addSql('ALTER TABLE event ADD name VARCHAR(30) NOT NULL');
        $this->addSql('ALTER TABLE event ADD date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE event ADD address VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE event ADD status VARCHAR(30) NOT NULL');
        $this->addSql('ALTER TABLE event DROP music_group_id');
        $this->addSql('ALTER TABLE event DROP title');
        $this->addSql('ALTER TABLE event DROP description');
        $this->addSql('ALTER TABLE event DROP lieu');
        $this->addSql('ALTER TABLE event DROP public');
        $this->addSql('ALTER TABLE event DROP event_start');
        $this->addSql('ALTER TABLE event DROP event_end');
        $this->addSql('ALTER TABLE event DROP background_color');
        $this->addSql('ALTER TABLE event DROP border_color');
        $this->addSql('ALTER TABLE event DROP text_color');
    }
}
