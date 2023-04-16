<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230416012511 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE code (id INT AUTO_INCREMENT NOT NULL, used_by_id INT DEFAULT NULL, lecture_id INT NOT NULL, hash LONGTEXT NOT NULL, url LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL, INDEX IDX_771530984C2B72A8 (used_by_id), INDEX IDX_7715309835E32FCD (lecture_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE course (id INT AUTO_INCREMENT NOT NULL, class VARCHAR(100) NOT NULL, period VARCHAR(30) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, description LONGTEXT DEFAULT NULL, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL, banner_url LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lecture (id INT AUTO_INCREMENT NOT NULL, event_id INT NOT NULL, lecturer VARCHAR(255) NOT NULL, location VARCHAR(255) NOT NULL, attendees_quantity INT NOT NULL, subtitle VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL, lecturer_image LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL, INDEX IDX_C167794871F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE participant (id INT AUTO_INCREMENT NOT NULL, kind VARCHAR(50) NOT NULL, ra VARCHAR(20) NOT NULL, name VARCHAR(100) NOT NULL, email VARCHAR(255) NOT NULL, reason LONGTEXT DEFAULT NULL, accepted_terms VARCHAR(1) NOT NULL, user_agent VARCHAR(255) NOT NULL, ip VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE participant_course (participant_id INT NOT NULL, course_id INT NOT NULL, INDEX IDX_A4E2D1389D1C3019 (participant_id), INDEX IDX_A4E2D138591CC992 (course_id), PRIMARY KEY(participant_id, course_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(100) NOT NULL, slug VARCHAR(100) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role_user (role_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_332CA4DDD60322AC (role_id), INDEX IDX_332CA4DDA76ED395 (user_id), PRIMARY KEY(role_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE code ADD CONSTRAINT FK_771530984C2B72A8 FOREIGN KEY (used_by_id) REFERENCES participant (id)');
        $this->addSql('ALTER TABLE code ADD CONSTRAINT FK_7715309835E32FCD FOREIGN KEY (lecture_id) REFERENCES lecture (id)');
        $this->addSql('ALTER TABLE lecture ADD CONSTRAINT FK_C167794871F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE participant_course ADD CONSTRAINT FK_A4E2D1389D1C3019 FOREIGN KEY (participant_id) REFERENCES participant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE participant_course ADD CONSTRAINT FK_A4E2D138591CC992 FOREIGN KEY (course_id) REFERENCES course (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE role_user ADD CONSTRAINT FK_332CA4DDD60322AC FOREIGN KEY (role_id) REFERENCES role (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE role_user ADD CONSTRAINT FK_332CA4DDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE code DROP FOREIGN KEY FK_771530984C2B72A8');
        $this->addSql('ALTER TABLE code DROP FOREIGN KEY FK_7715309835E32FCD');
        $this->addSql('ALTER TABLE lecture DROP FOREIGN KEY FK_C167794871F7E88B');
        $this->addSql('ALTER TABLE participant_course DROP FOREIGN KEY FK_A4E2D1389D1C3019');
        $this->addSql('ALTER TABLE participant_course DROP FOREIGN KEY FK_A4E2D138591CC992');
        $this->addSql('ALTER TABLE role_user DROP FOREIGN KEY FK_332CA4DDD60322AC');
        $this->addSql('ALTER TABLE role_user DROP FOREIGN KEY FK_332CA4DDA76ED395');
        $this->addSql('DROP TABLE code');
        $this->addSql('DROP TABLE course');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE lecture');
        $this->addSql('DROP TABLE participant');
        $this->addSql('DROP TABLE participant_course');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE role_user');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin`');
    }
}
