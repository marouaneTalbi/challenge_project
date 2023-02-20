<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230215180758 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event ADD title VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE event ADD event_start TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE event ADD event_end TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE event ADD background_color VARCHAR(7) NOT NULL');
        $this->addSql('ALTER TABLE event ADD border_color VARCHAR(7) NOT NULL');
        $this->addSql('ALTER TABLE event ADD text_color VARCHAR(7) NOT NULL');
        $this->addSql('ALTER TABLE event DROP name');
        $this->addSql('ALTER TABLE event DROP type');
        $this->addSql('ALTER TABLE event DROP heure');
        $this->addSql('ALTER TABLE event DROP date');
        $this->addSql('ALTER TABLE event ALTER lieu TYPE VARCHAR(100)');
        $this->addSql('ALTER TABLE event ALTER description TYPE TEXT');
        $this->addSql('ALTER TABLE event ALTER description SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE event ADD name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE event ADD type VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE event ADD heure TIME(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE event ADD date DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE event DROP title');
        $this->addSql('ALTER TABLE event DROP event_start');
        $this->addSql('ALTER TABLE event DROP event_end');
        $this->addSql('ALTER TABLE event DROP background_color');
        $this->addSql('ALTER TABLE event DROP border_color');
        $this->addSql('ALTER TABLE event DROP text_color');
        $this->addSql('ALTER TABLE event ALTER description TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE event ALTER description DROP NOT NULL');
        $this->addSql('ALTER TABLE event ALTER lieu TYPE VARCHAR(255)');
    }
}
