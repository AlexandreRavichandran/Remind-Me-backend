<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211002192517 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create table for music order on list';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_music_list (id INT AUTO_INCREMENT NOT NULL, list_id INT NOT NULL, music_id INT NOT NULL, list_order INT NOT NULL, INDEX IDX_B79E3A6F3DAE168B (list_id), INDEX IDX_B79E3A6F399BBB13 (music_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_music_list ADD CONSTRAINT FK_B79E3A6F3DAE168B FOREIGN KEY (list_id) REFERENCES listing (id)');
        $this->addSql('ALTER TABLE user_music_list ADD CONSTRAINT FK_B79E3A6F399BBB13 FOREIGN KEY (music_id) REFERENCES music (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user_music_list');
    }
}
