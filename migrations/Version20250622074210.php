<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250622074210 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE date_daily (id INT AUTO_INCREMENT NOT NULL, habit_id INT NOT NULL, mon TINYINT(1) NOT NULL, tue TINYINT(1) NOT NULL, wed TINYINT(1) NOT NULL, thu TINYINT(1) NOT NULL, fri TINYINT(1) NOT NULL, sat TINYINT(1) NOT NULL, sun TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE date_repeat_per_month (id INT AUTO_INCREMENT NOT NULL, habit_id INT NOT NULL, day INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE date_weekly (id INT AUTO_INCREMENT NOT NULL, habit_id INT NOT NULL, count_days INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE habits (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, icon_url VARCHAR(255) DEFAULT NULL, quote VARCHAR(255) DEFAULT NULL, date_type VARCHAR(255) NOT NULL, goal_in_days INT DEFAULT NULL, begin_date DATETIME DEFAULT NULL, notification_id INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE notifications (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, payload VARCHAR(255) DEFAULT NULL, is_active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE purposes (id INT AUTO_INCREMENT NOT NULL, target_type VARCHAR(255) NOT NULL, target_id INT NOT NULL, cup_count INT DEFAULT NULL, millimeter_count INT DEFAULT NULL, minute_count INT DEFAULT NULL, hour_count INT DEFAULT NULL, kilometer_count INT DEFAULT NULL, pages_count INT DEFAULT NULL, new_count INT DEFAULT NULL, check_manually TINYINT(1) DEFAULT NULL, check_auto TINYINT(1) DEFAULT NULL, check_close TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE tasks (id INT AUTO_INCREMENT NOT NULL, habit_id INT NOT NULL, task_type VARCHAR(255) NOT NULL, title_task VARCHAR(255) NOT NULL, pomodoro_count INT DEFAULT NULL, pomodoro_id INT DEFAULT NULL, notification_id INT DEFAULT NULL, begin_date DATETIME DEFAULT NULL, due_date DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pomodor_history ADD target_type VARCHAR(255) NOT NULL, ADD target_id INT NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP TABLE date_daily
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE date_repeat_per_month
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE date_weekly
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE habits
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE notifications
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE purposes
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE tasks
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pomodor_history DROP target_type, DROP target_id
        SQL);
    }
}
