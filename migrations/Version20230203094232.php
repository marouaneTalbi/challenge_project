<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230203094232 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE music_group_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE music_group (id INT NOT NULL, manager_id INT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A493F0D783E3463 ON music_group (manager_id)');
        $this->addSql('CREATE TABLE music_group_user (music_group_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(music_group_id, user_id))');
        $this->addSql('CREATE INDEX IDX_5CAD4D139478561 ON music_group_user (music_group_id)');
        $this->addSql('CREATE INDEX IDX_5CAD4D1A76ED395 ON music_group_user (user_id)');
        $this->addSql('ALTER TABLE music_group ADD CONSTRAINT FK_A493F0D783E3463 FOREIGN KEY (manager_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE music_group_user ADD CONSTRAINT FK_5CAD4D139478561 FOREIGN KEY (music_group_id) REFERENCES music_group (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE music_group_user ADD CONSTRAINT FK_5CAD4D1A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ALTER is_enabled DROP DEFAULT');
        $this->addSql('ALTER TABLE "user" ALTER is_deleted DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        // $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE music_group_id_seq CASCADE');
        $this->addSql('ALTER TABLE music_group DROP CONSTRAINT FK_A493F0D783E3463');
        $this->addSql('ALTER TABLE music_group_user DROP CONSTRAINT FK_5CAD4D139478561');
        $this->addSql('ALTER TABLE music_group_user DROP CONSTRAINT FK_5CAD4D1A76ED395');
        $this->addSql('DROP TABLE music_group');
        $this->addSql('DROP TABLE music_group_user');
        $this->addSql('ALTER TABLE "user" ALTER is_enabled SET DEFAULT false');
        $this->addSql('ALTER TABLE "user" ALTER is_deleted SET DEFAULT false');
    }
}
