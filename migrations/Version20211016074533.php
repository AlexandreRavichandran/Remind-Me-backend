<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211016074533 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book ADD picture_url VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE movie ADD picture_url VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE music ADD picture_url VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book DROP picture_url');
        $this->addSql('ALTER TABLE movie DROP picture_url');
        $this->addSql('ALTER TABLE music DROP picture_url');
    }
}
