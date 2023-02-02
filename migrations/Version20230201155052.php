<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230201155052 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE event_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE event (id INT NOT NULL, name VARCHAR(30) NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, address VARCHAR(100) NOT NULL, status VARCHAR(30) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE group_user DROP CONSTRAINT fk_a4c98d39fe54d947');
        $this->addSql('ALTER TABLE group_user DROP CONSTRAINT fk_a4c98d39a76ed395');
        $this->addSql('ALTER TABLE user_group DROP CONSTRAINT fk_8f02bf9da76ed395');
        $this->addSql('ALTER TABLE user_group DROP CONSTRAINT fk_8f02bf9dfe54d947');
        $this->addSql('DROP TABLE group_user');
        $this->addSql('DROP TABLE user_group');
        $this->addSql('ALTER TABLE "group" DROP manager');
        $this->addSql('ALTER TABLE "group" ALTER name TYPE VARCHAR(30)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE event_id_seq CASCADE');
        $this->addSql('CREATE TABLE group_user (group_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(group_id, user_id))');
        $this->addSql('CREATE INDEX idx_a4c98d39a76ed395 ON group_user (user_id)');
        $this->addSql('CREATE INDEX idx_a4c98d39fe54d947 ON group_user (group_id)');
        $this->addSql('CREATE TABLE user_group (user_id INT NOT NULL, group_id INT NOT NULL, PRIMARY KEY(user_id, group_id))');
        $this->addSql('CREATE INDEX idx_8f02bf9dfe54d947 ON user_group (group_id)');
        $this->addSql('CREATE INDEX idx_8f02bf9da76ed395 ON user_group (user_id)');
        $this->addSql('ALTER TABLE group_user ADD CONSTRAINT fk_a4c98d39fe54d947 FOREIGN KEY (group_id) REFERENCES "group" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE group_user ADD CONSTRAINT fk_a4c98d39a76ed395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_group ADD CONSTRAINT fk_8f02bf9da76ed395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_group ADD CONSTRAINT fk_8f02bf9dfe54d947 FOREIGN KEY (group_id) REFERENCES "group" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE event');
        $this->addSql('ALTER TABLE "group" ADD manager VARCHAR(25) NOT NULL');
        $this->addSql('ALTER TABLE "group" ALTER name TYPE VARCHAR(25)');
    }
}
