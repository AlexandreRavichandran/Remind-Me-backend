<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211003054537 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create table for book order on list';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_book_list (id INT AUTO_INCREMENT NOT NULL, list_id INT NOT NULL, book_id INT NOT NULL, list_order INT NOT NULL, INDEX IDX_5DB53EF43DAE168B (list_id), INDEX IDX_5DB53EF416A2B381 (book_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_book_list ADD CONSTRAINT FK_5DB53EF43DAE168B FOREIGN KEY (list_id) REFERENCES listing (id)');
        $this->addSql('ALTER TABLE user_book_list ADD CONSTRAINT FK_5DB53EF416A2B381 FOREIGN KEY (book_id) REFERENCES book (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user_book_list');
    }
}
