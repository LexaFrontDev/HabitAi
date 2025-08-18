<?php

declare(strict_types=1);

namespace Migrations\V1\Tasks;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250802083849_create_tasks_history_table extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $sm = $this->connection->getSchemaManager();

        if (!$sm->tablesExist(['tasks_history'])) {
            $this->addSql('CREATE TABLE tasks_history (
                id INT AUTO_INCREMENT NOT NULL COMMENT \'ID записи истории задачи\',
                tasks_id INT NOT NULL COMMENT \'ID записи истории задачи\',
                user_id INT NOT NULL COMMENT \'ID пользователя\',
                time_close DATETIME DEFAULT NULL COMMENT \'Дата и время закрытия задачи\',
                time_close_month VARCHAR(255) DEFAULT NULL COMMENT \'Месяц закрытия задачи (например, 2025-08)\',
                created_at DATETIME DEFAULT NULL COMMENT \'Дата создания записи\',
                updated_at DATETIME DEFAULT NULL COMMENT \'Дата обновления записи\',
                is_delete TINYINT(1) NOT NULL COMMENT \'Флаг логического удаления\',
                PRIMARY KEY(id),
                INDEX IDX_USER_ID (user_id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        }
    }

    public function down(Schema $schema): void
    {
        $sm = $this->connection->getSchemaManager();

        if ($sm->tablesExist(['tasks_history'])) {
            $this->addSql('DROP TABLE tasks_history');
        }
    }
}
