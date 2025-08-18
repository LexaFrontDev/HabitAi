<?php

declare(strict_types=1);

namespace Migrations\V1\Different;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250815092840_create_table_statistic extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Создание таблицы statistic для хранения агрегированной статистики привычек';
    }

    public function up(Schema $schema): void
    {
        if (!$schema->hasTable('statistic')) {
            $this->addSql('
                CREATE TABLE statistic (
                    id INT UNSIGNED AUTO_INCREMENT NOT NULL,
                    user_id INT UNSIGNED NOT NULL,
                    stat_type VARCHAR(50) NOT NULL COMMENT \'Тип статистики (habit, task, etc.)\',
                    static_id INT UNSIGNED NOT NULL COMMENT \'ID сущности, к которой относится статистика\',
                    year JSON NOT NULL COMMENT \'Данные по годам\',
                    created_at DATETIME DEFAULT NULL COMMENT \'Дата создания записи\',
                    updated_at DATETIME DEFAULT NULL COMMENT \'Дата обновления записи\',
                    is_delete TINYINT(1) NOT NULL DEFAULT 0 COMMENT \'Флаг логического удаления\',
                    PRIMARY KEY(id),
                    INDEX IDX_habits_statistic_user_id (user_id),
                    INDEX IDX_habits_statistic_static_id (static_id)
                ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
            ');}
    }

    public function down(Schema $schema): void
    {
        if ($schema->hasTable('statistic')) {
            $this->addSql('DROP TABLE habits_statistic');
        }
    }
}
