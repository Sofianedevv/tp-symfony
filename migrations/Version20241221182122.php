<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241221182122 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_favorites (user_id INT NOT NULL, subject_id INT NOT NULL, INDEX IDX_E489ED11A76ED395 (user_id), INDEX IDX_E489ED1123EDC87 (subject_id), PRIMARY KEY(user_id, subject_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_favorites ADD CONSTRAINT FK_E489ED11A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_favorites ADD CONSTRAINT FK_E489ED1123EDC87 FOREIGN KEY (subject_id) REFERENCES subject (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_subject DROP FOREIGN KEY FK_A3C3207023EDC87');
        $this->addSql('ALTER TABLE user_subject DROP FOREIGN KEY FK_A3C32070A76ED395');
        $this->addSql('DROP TABLE user_subject');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_subject (user_id INT NOT NULL, subject_id INT NOT NULL, INDEX IDX_A3C3207023EDC87 (subject_id), INDEX IDX_A3C32070A76ED395 (user_id), PRIMARY KEY(user_id, subject_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE user_subject ADD CONSTRAINT FK_A3C3207023EDC87 FOREIGN KEY (subject_id) REFERENCES subject (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_subject ADD CONSTRAINT FK_A3C32070A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_favorites DROP FOREIGN KEY FK_E489ED11A76ED395');
        $this->addSql('ALTER TABLE user_favorites DROP FOREIGN KEY FK_E489ED1123EDC87');
        $this->addSql('DROP TABLE user_favorites');
    }
}
