<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231211054603 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE author (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, deleted_at DATETIME DEFAULT NULL COMMENT \'削除日時(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'作成日時(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'更新日時(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'著者テーブル\' ');
        $this->addSql('CREATE TABLE book (id INT AUTO_INCREMENT NOT NULL, isbn VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, deleted_at DATETIME DEFAULT NULL COMMENT \'削除日時(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'作成日時(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'更新日時(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'書籍テーブル\' ');
        $this->addSql('CREATE TABLE book_user (book_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_940E9D4116A2B381 (book_id), INDEX IDX_940E9D41A76ED395 (user_id), PRIMARY KEY(book_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, deleted_at DATETIME DEFAULT NULL COMMENT \'削除日時(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'作成日時(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'更新日時(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'利用者テーブル\' ');
        $this->addSql('ALTER TABLE book_user ADD CONSTRAINT FK_940E9D4116A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE book_user ADD CONSTRAINT FK_940E9D41A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book_user DROP FOREIGN KEY FK_940E9D4116A2B381');
        $this->addSql('ALTER TABLE book_user DROP FOREIGN KEY FK_940E9D41A76ED395');
        $this->addSql('DROP TABLE author');
        $this->addSql('DROP TABLE book');
        $this->addSql('DROP TABLE book_user');
        $this->addSql('DROP TABLE user');
    }
}
