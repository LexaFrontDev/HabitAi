<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250623032051 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE habits_data_juntion (id INT AUTO_INCREMENT NOT NULL, habits_id INT NOT NULL, data_id INT NOT NULL, data_type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE habits_pomodor_junction (id INT AUTO_INCREMENT NOT NULL, pomodor_id INT NOT NULL, habits_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE date_daily DROP habit_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE date_repeat_per_month DROP habit_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE date_weekly DROP habit_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE habits DROP date_type
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pomodor_history DROP target_type, DROP target_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE purposes DROP target_type, DROP target_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tasks ADD purpose_id INT DEFAULT NULL, DROP habit_id, DROP pomodoro_count, DROP pomodoro_id
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP TABLE habits_data_juntion
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE habits_pomodor_junction
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE date_daily ADD habit_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE date_weekly ADD habit_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tasks ADD habit_id INT NOT NULL, ADD pomodoro_id INT DEFAULT NULL, CHANGE purpose_id pomodoro_count INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE purposes ADD target_type VARCHAR(255) NOT NULL, ADD target_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE date_repeat_per_month ADD habit_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE habits ADD date_type VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pomodor_history ADD target_type VARCHAR(255) NOT NULL, ADD target_id INT DEFAULT NULL
        SQL);
    }
}
