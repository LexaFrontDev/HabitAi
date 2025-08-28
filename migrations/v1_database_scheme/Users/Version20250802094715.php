<?php

declare(strict_types=1);

namespace Migrations\V1\Users;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250802094715 extends AbstractMigration
{
    private array $columnsCache = [];

    public function getDescription(): string
    {
        return 'Safe migration: modifies Tasks, Habits, Users and other tables with column existence checks.';
    }

    private function hasColumn(string $table, string $column): bool
    {
        if (!isset($this->columnsCache[$table])) {
            $columns = $this->connection
                ->fetchAllAssociative("SHOW COLUMNS FROM `$table`");

            $this->columnsCache[$table] = array_map(
                fn ($col) => strtolower($col['Field']),
                $columns
            );
        }

        return in_array(strtolower($column), $this->columnsCache[$table], true);
    }

    private function hasIndex(string $table, string $indexName): bool
    {
        $indexes = $this->connection->fetchAllAssociative("SHOW INDEX FROM `$table`");

        foreach ($indexes as $index) {
            if ($index['Key_name'] === $indexName) {
                return true;
            }
        }

        return false;
    }

    public function up(Schema $schema): void
    {
        $platform = $this->connection->getDatabasePlatform()->getName();
        $this->abortIf('mysql' !== $platform, 'Migration supports only MySQL.');

        $tables = [
            'Habits',
            'Tasks',
            'date_daily',
            'date_repeat_per_month',
            'date_weekly',
            'habits_data_juntion',
            'habits_pomodor_junction',
            'matric_column',
            'matric_junction',
            'notifications',
            'purposes',
            'refresh_tokens',
            'Users',
        ];

        foreach ($tables as $table) {
            if (!$this->hasColumn($table, 'created_at')) {
                $this->addSql("ALTER TABLE `$table` ADD created_at DATETIME DEFAULT NULL COMMENT 'Дата создания записи'");
            }

            if (!$this->hasColumn($table, 'updated_at')) {
                $this->addSql("ALTER TABLE `$table` ADD updated_at DATETIME DEFAULT NULL COMMENT 'Дата обновления записи'");
            }

            if (!$this->hasColumn($table, 'is_delete')) {
                $this->addSql("ALTER TABLE `$table` ADD is_delete TINYINT(1) NOT NULL COMMENT 'Флаг логического удаления'");
            }
        }

        if (!$this->hasColumn('matric_column', 'user_id')) {
            $this->addSql('ALTER TABLE matric_column ADD user_id INT NOT NULL');
        }

        if (!$this->hasColumn('Tasks', 'time')) {
            $this->addSql('ALTER TABLE Tasks
                ADD time VARCHAR(255) DEFAULT NULL,
                ADD start_date VARCHAR(255) DEFAULT NULL,
                ADD start_time VARCHAR(255) DEFAULT NULL,
                ADD end_date VARCHAR(255) DEFAULT NULL,
                ADD end_time VARCHAR(255) DEFAULT NULL,
                ADD repeat_mode VARCHAR(255) DEFAULT NULL
            ');
        }

        if ($this->hasColumn('Tasks', 'task_type')) {
            $this->addSql('ALTER TABLE Tasks DROP task_type');
        }

        if ($this->hasColumn('Tasks', 'notification_id')) {
            $this->addSql('ALTER TABLE Tasks DROP notification_id');
        }

        if ($this->hasColumn('Tasks', 'due_date')) {
            $this->addSql('ALTER TABLE Tasks DROP due_date');
        }

        if ($this->hasIndex('tasks_history', 'IDX_USER_ID')) {
            $this->addSql('DROP INDEX IDX_USER_ID ON tasks_history');
        }

        if ($this->hasColumn('tasks_history', 'tasks_id')) {
            $this->addSql("ALTER TABLE tasks_history CHANGE tasks_id tasks_id INT NOT NULL COMMENT 'ID задачи'");
        }
    }

    public function down(Schema $schema): void
    {
        $platform = $this->connection->getDatabasePlatform()->getName();
        $this->abortIf('mysql' !== $platform, 'Migration supports only MySQL.');

        $tables = [
            'Habits',
            'Tasks',
            'date_daily',
            'date_repeat_per_month',
            'date_weekly',
            'habits_data_juntion',
            'habits_pomodor_junction',
            'matric_column',
            'matric_junction',
            'notifications',
            'purposes',
            'refresh_tokens',
            'Users',
        ];

        foreach ($tables as $table) {
            if ($this->hasColumn($table, 'created_at')) {
                $this->addSql("ALTER TABLE `$table` DROP COLUMN created_at");
            }

            if ($this->hasColumn($table, 'updated_at')) {
                $this->addSql("ALTER TABLE `$table` DROP COLUMN updated_at");
            }

            if ($this->hasColumn($table, 'is_delete')) {
                $this->addSql("ALTER TABLE `$table` DROP COLUMN is_delete");
            }
        }

        if ($this->hasColumn('matric_column', 'user_id')) {
            $this->addSql('ALTER TABLE matric_column DROP COLUMN user_id');
        }

        if ($this->hasColumn('Tasks', 'time')) {
            $this->addSql('ALTER TABLE Tasks
                DROP COLUMN time,
                DROP COLUMN start_date,
                DROP COLUMN start_time,
                DROP COLUMN end_date,
                DROP COLUMN end_time,
                DROP COLUMN repeat_mode
            ');
        }

        if (!$this->hasColumn('Tasks', 'task_type')) {
            $this->addSql('ALTER TABLE Tasks ADD task_type VARCHAR(255) DEFAULT NULL');
        }

        if (!$this->hasColumn('Tasks', 'notification_id')) {
            $this->addSql('ALTER TABLE Tasks ADD notification_id INT DEFAULT NULL');
        }

        if (!$this->hasColumn('Tasks', 'due_date')) {
            $this->addSql('ALTER TABLE Tasks ADD due_date DATETIME DEFAULT NULL');
        }

        if (!$this->hasIndex('tasks_history', 'IDX_USER_ID') && $this->hasColumn('tasks_history', 'user_id')) {
            $this->addSql('CREATE INDEX IDX_USER_ID ON tasks_history (user_id)');
        }

        if ($this->hasColumn('tasks_history', 'tasks_id')) {
            $this->addSql('ALTER TABLE tasks_history CHANGE tasks_id tasks_id INT NOT NULL');
        }
    }
}
