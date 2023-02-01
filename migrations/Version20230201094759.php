<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230201094759 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE "group_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "group" (id INT NOT NULL, name VARCHAR(25) NOT NULL, manager VARCHAR(25) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE "user" ALTER is_enabled DROP DEFAULT');
        $this->addSql('ALTER TABLE "user" ALTER is_deleted DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE "group_id_seq" CASCADE');
        $this->addSql('DROP TABLE "group"');
        $this->addSql('ALTER TABLE "user" ALTER is_enabled SET DEFAULT false');
        $this->addSql('ALTER TABLE "user" ALTER is_deleted SET DEFAULT false');
    }
}
