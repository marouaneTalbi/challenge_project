<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230201134911 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "user" ADD description VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD promotion_link TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD status VARCHAR(20) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD apply VARCHAR(20) DEFAULT NULL');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "user" DROP description');
        $this->addSql('ALTER TABLE "user" DROP promotion_link');
        $this->addSql('ALTER TABLE "user" DROP status');
        $this->addSql('ALTER TABLE "user" DROP apply');

    }
}
