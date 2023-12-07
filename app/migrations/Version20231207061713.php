<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231207061713 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE course_for_user (id INT AUTO_INCREMENT NOT NULL, course_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_F792DA8A591CC992 (course_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE courses (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, name VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE material (id INT AUTO_INCREMENT NOT NULL, course_id INT NOT NULL, INDEX IDX_7CBE7595591CC992 (course_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE task (id INT AUTO_INCREMENT NOT NULL, course_id INT NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, answers JSON NOT NULL, right_answers JSON NOT NULL, INDEX IDX_527EDB25591CC992 (course_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE task_user_answer (task_id INT NOT NULL, user_id INT NOT NULL, answer LONGTEXT NOT NULL, PRIMARY KEY(task_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_teacher (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, accept TINYINT(1) DEFAULT 0 NOT NULL, teacher_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE course_for_user ADD CONSTRAINT FK_F792DA8A591CC992 FOREIGN KEY (course_id) REFERENCES courses (id)');
        $this->addSql('ALTER TABLE material ADD CONSTRAINT FK_7CBE7595591CC992 FOREIGN KEY (course_id) REFERENCES courses (id)');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25591CC992 FOREIGN KEY (course_id) REFERENCES courses (id)');
        $this->addSql('ALTER TABLE task_user_answer ADD CONSTRAINT FK_F9FAFC2A8DB60186 FOREIGN KEY (task_id) REFERENCES task (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE course_for_user DROP FOREIGN KEY FK_F792DA8A591CC992');
        $this->addSql('ALTER TABLE material DROP FOREIGN KEY FK_7CBE7595591CC992');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB25591CC992');
        $this->addSql('ALTER TABLE task_user_answer DROP FOREIGN KEY FK_F9FAFC2A8DB60186');
        $this->addSql('DROP TABLE course_for_user');
        $this->addSql('DROP TABLE courses');
        $this->addSql('DROP TABLE material');
        $this->addSql('DROP TABLE task');
        $this->addSql('DROP TABLE task_user_answer');
        $this->addSql('DROP TABLE user_teacher');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
