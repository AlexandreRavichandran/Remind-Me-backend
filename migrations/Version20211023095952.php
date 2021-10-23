<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211023095952 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE book (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, author VARCHAR(255) NOT NULL, released_at VARCHAR(255) NOT NULL, api_code VARCHAR(255) NOT NULL, picture_url LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE movie (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, released_at VARCHAR(255) NOT NULL, api_code VARCHAR(255) NOT NULL, picture_url LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE music (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, released_at VARCHAR(255) DEFAULT NULL, type SMALLINT NOT NULL, artist VARCHAR(255) DEFAULT NULL, api_code VARCHAR(255) NOT NULL, picture_url LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, pseudonym VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_book_list (id INT AUTO_INCREMENT NOT NULL, book_id INT NOT NULL, user_id INT NOT NULL, list_order INT NOT NULL, INDEX IDX_5DB53EF416A2B381 (book_id), INDEX IDX_5DB53EF4A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_movie_list (id INT AUTO_INCREMENT NOT NULL, movie_id INT NOT NULL, user_id INT NOT NULL, list_order INT NOT NULL, INDEX IDX_10C91FF98F93B6FC (movie_id), INDEX IDX_10C91FF9A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_music_list (id INT AUTO_INCREMENT NOT NULL, music_id INT NOT NULL, user_id INT NOT NULL, list_order INT NOT NULL, INDEX IDX_B79E3A6F399BBB13 (music_id), INDEX IDX_B79E3A6FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_book_list ADD CONSTRAINT FK_5DB53EF416A2B381 FOREIGN KEY (book_id) REFERENCES book (id)');
        $this->addSql('ALTER TABLE user_book_list ADD CONSTRAINT FK_5DB53EF4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_movie_list ADD CONSTRAINT FK_10C91FF98F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id)');
        $this->addSql('ALTER TABLE user_movie_list ADD CONSTRAINT FK_10C91FF9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_music_list ADD CONSTRAINT FK_B79E3A6F399BBB13 FOREIGN KEY (music_id) REFERENCES music (id)');
        $this->addSql('ALTER TABLE user_music_list ADD CONSTRAINT FK_B79E3A6FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_book_list DROP FOREIGN KEY FK_5DB53EF416A2B381');
        $this->addSql('ALTER TABLE user_movie_list DROP FOREIGN KEY FK_10C91FF98F93B6FC');
        $this->addSql('ALTER TABLE user_music_list DROP FOREIGN KEY FK_B79E3A6F399BBB13');
        $this->addSql('ALTER TABLE user_book_list DROP FOREIGN KEY FK_5DB53EF4A76ED395');
        $this->addSql('ALTER TABLE user_movie_list DROP FOREIGN KEY FK_10C91FF9A76ED395');
        $this->addSql('ALTER TABLE user_music_list DROP FOREIGN KEY FK_B79E3A6FA76ED395');
        $this->addSql('DROP TABLE book');
        $this->addSql('DROP TABLE movie');
        $this->addSql('DROP TABLE music');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_book_list');
        $this->addSql('DROP TABLE user_movie_list');
        $this->addSql('DROP TABLE user_music_list');
    }
}
