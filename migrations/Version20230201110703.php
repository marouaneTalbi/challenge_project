<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230201110703 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE event_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE news_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE event (id INT NOT NULL, name VARCHAR(30) NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, address VARCHAR(150) NOT NULL, status VARCHAR(20) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE group_user (group_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(group_id, user_id))');
        $this->addSql('CREATE INDEX IDX_A4C98D39FE54D947 ON group_user (group_id)');
        $this->addSql('CREATE INDEX IDX_A4C98D39A76ED395 ON group_user (user_id)');
        $this->addSql('CREATE TABLE news (id INT NOT NULL, author_id INT NOT NULL, post VARCHAR(255) NOT NULL, status VARCHAR(20) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1DD39950F675F31B ON news (author_id)');
        $this->addSql('CREATE TABLE user_group (user_id INT NOT NULL, group_id INT NOT NULL, PRIMARY KEY(user_id, group_id))');
        $this->addSql('CREATE INDEX IDX_8F02BF9DA76ED395 ON user_group (user_id)');
        $this->addSql('CREATE INDEX IDX_8F02BF9DFE54D947 ON user_group (group_id)');
        $this->addSql('ALTER TABLE group_user ADD CONSTRAINT FK_A4C98D39FE54D947 FOREIGN KEY (group_id) REFERENCES "group" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE group_user ADD CONSTRAINT FK_A4C98D39A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE news ADD CONSTRAINT FK_1DD39950F675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_group ADD CONSTRAINT FK_8F02BF9DA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_group ADD CONSTRAINT FK_8F02BF9DFE54D947 FOREIGN KEY (group_id) REFERENCES "group" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "group" ADD group_news_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "group" ADD events_id INT NOT NULL');
        $this->addSql('ALTER TABLE "group" ADD CONSTRAINT FK_6DC044C533A40DAC FOREIGN KEY (group_news_id) REFERENCES news (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "group" ADD CONSTRAINT FK_6DC044C59D6A1065 FOREIGN KEY (events_id) REFERENCES event (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_6DC044C533A40DAC ON "group" (group_news_id)');
        $this->addSql('CREATE INDEX IDX_6DC044C59D6A1065 ON "group" (events_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "group" DROP CONSTRAINT FK_6DC044C59D6A1065');
        $this->addSql('ALTER TABLE "group" DROP CONSTRAINT FK_6DC044C533A40DAC');
        $this->addSql('DROP SEQUENCE event_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE news_id_seq CASCADE');
        $this->addSql('ALTER TABLE group_user DROP CONSTRAINT FK_A4C98D39FE54D947');
        $this->addSql('ALTER TABLE group_user DROP CONSTRAINT FK_A4C98D39A76ED395');
        $this->addSql('ALTER TABLE news DROP CONSTRAINT FK_1DD39950F675F31B');
        $this->addSql('ALTER TABLE user_group DROP CONSTRAINT FK_8F02BF9DA76ED395');
        $this->addSql('ALTER TABLE user_group DROP CONSTRAINT FK_8F02BF9DFE54D947');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE group_user');
        $this->addSql('DROP TABLE news');
        $this->addSql('DROP TABLE user_group');
        $this->addSql('DROP INDEX IDX_6DC044C533A40DAC');
        $this->addSql('DROP INDEX IDX_6DC044C59D6A1065');
        $this->addSql('ALTER TABLE "group" DROP group_news_id');
        $this->addSql('ALTER TABLE "group" DROP events_id');
    }
}
