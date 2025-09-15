<?php

declare(strict_types=1);

namespace Migrations\V1\Users;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250802094715 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Safe migration: modifies tasks, habits, users and other tables with column existence checks for PostgreSQL.';
    }

    private function hasColumn(string $table, string $column): bool
    {
        $sm = $this->connection->createSchemaManager();
        if (!$sm->tablesExist([$table])) {
            return false;
        }

        $columns = $sm->listTableColumns($table);
        return isset($columns[$column]);
    }

    private function hasIndex(string $table, string $indexName): bool
    {
        $sm = $this->connection->createSchemaManager();
        if (!$sm->tablesExist([$table])) {
            return false;
        }

        $indexes = $sm->listTableIndexes($table);
        return isset($indexes[$indexName]);
    }

    public function up(Schema $schema): void
    {
        $tables = [
            'habits',
            'tasks',
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
            'users',
        ];

        foreach ($tables as $table) {
            if ($this->hasColumn($table, 'created_at') === false) {
                $this->addSql("ALTER TABLE $table ADD COLUMN created_at TIMESTAMP DEFAULT NULL");
            }

            if ($this->hasColumn($table, 'updated_at') === false) {
                $this->addSql("ALTER TABLE $table ADD COLUMN updated_at TIMESTAMP DEFAULT NULL");
            }

            if ($this->hasColumn($table, 'is_delete') === false) {
                $this->addSql("ALTER TABLE $table ADD COLUMN is_delete BOOLEAN NOT NULL DEFAULT FALSE");
            }
        }

        if ($this->hasColumn('matric_column', 'user_id') === false) {
            $this->addSql('ALTER TABLE matric_column ADD COLUMN user_id INTEGER NOT NULL');
        }

        if ($this->hasColumn('tasks', 'time') === false) {
            $this->addSql('ALTER TABLE tasks
                ADD COLUMN time VARCHAR(255) DEFAULT NULL,
                ADD COLUMN start_date VARCHAR(255) DEFAULT NULL,
                ADD COLUMN start_time VARCHAR(255) DEFAULT NULL,
                ADD COLUMN end_date VARCHAR(255) DEFAULT NULL,
                ADD COLUMN end_time VARCHAR(255) DEFAULT NULL,
                ADD COLUMN repeat_mode VARCHAR(255) DEFAULT NULL
            ');
        }

        if ($this->hasColumn('tasks', 'task_type')) {
            $this->addSql('ALTER TABLE tasks DROP COLUMN task_type');
        }

        if ($this->hasColumn('tasks', 'notification_id')) {
            $this->addSql('ALTER TABLE tasks DROP COLUMN notification_id');
        }

        if ($this->hasColumn('tasks', 'due_date')) {
            $this->addSql('ALTER TABLE tasks DROP COLUMN due_date');
        }

        if ($this->hasIndex('tasks_history', 'idx_user_id')) {
            $this->addSql('DROP INDEX IF EXISTS idx_user_id');
        }

        if ($this->hasColumn('tasks_history', 'tasks_id')) {
            $this->addSql('ALTER TABLE tasks_history ALTER COLUMN tasks_id TYPE INTEGER');
            $this->addSql('ALTER TABLE tasks_history ALTER COLUMN tasks_id SET NOT NULL');
        }
    }

    public function down(Schema $schema): void
    {
        $tables = [
            'habits',
            'tasks',
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
            'users',
        ];

        foreach ($tables as $table) {
            if ($this->hasColumn($table, 'created_at')) {
                $this->addSql("ALTER TABLE $table DROP COLUMN created_at");
            }

            if ($this->hasColumn($table, 'updated_at')) {
                $this->addSql("ALTER TABLE $table DROP COLUMN updated_at");
            }

            if ($this->hasColumn($table, 'is_delete')) {
                $this->addSql("ALTER TABLE $table DROP COLUMN is_delete");
            }
        }

        if ($this->hasColumn('matric_column', 'user_id')) {
            $this->addSql('ALTER TABLE matric_column DROP COLUMN user_id');
        }

        if ($this->hasColumn('tasks', 'time')) {
            $this->addSql('ALTER TABLE tasks
                DROP COLUMN time,
                DROP COLUMN start_date,
                DROP COLUMN start_time,
                DROP COLUMN end_date,
                DROP COLUMN end_time,
                DROP COLUMN repeat_mode
            ');
        }

        if ($this->hasColumn('tasks', 'task_type') === false) {
            $this->addSql('ALTER TABLE tasks ADD COLUMN task_type VARCHAR(255) DEFAULT NULL');
        }

        if ($this->hasColumn('tasks', 'notification_id') === false) {
            $this->addSql('ALTER TABLE tasks ADD COLUMN notification_id INTEGER DEFAULT NULL');
        }

        if ($this->hasColumn('tasks', 'due_date') === false) {
            $this->addSql('ALTER TABLE tasks ADD COLUMN due_date TIMESTAMP DEFAULT NULL');
        }

        if ($this->hasIndex('tasks_history', 'idx_user_id') === false && $this->hasColumn('tasks_history', 'user_id')) {
            $this->addSql('CREATE INDEX IF NOT EXISTS idx_user_id ON tasks_history (user_id)');
        }

        if ($this->hasColumn('tasks_history', 'tasks_id')) {
            $this->addSql('ALTER TABLE tasks_history ALTER COLUMN tasks_id TYPE INTEGER');
            $this->addSql('ALTER TABLE tasks_history ALTER COLUMN tasks_id SET NOT NULL');
        }
    }
}
