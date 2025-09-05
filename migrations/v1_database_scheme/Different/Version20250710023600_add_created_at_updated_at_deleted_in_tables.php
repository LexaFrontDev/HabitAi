<?php

declare(strict_types=1);

namespace Migrations\V1\Different;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250710023600Addcreatedatupdatedatdeletedintables extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add created_at, updated_at, is_delete columns to multiple tables safely';
    }

    private function tableExists(Schema $schema, string $tableName): bool
    {
        return $schema->hasTable($tableName);
    }

    private function columnExists(Schema $schema, string $tableName, string $columnName): bool
    {
        if (!$this->tableExists($schema, $tableName)) {
            return false;
        }

        return $schema->getTable($tableName)->hasColumn($columnName);
    }

    public function up(Schema $schema): void
    {
        $tables = [
            'date_daily',
            'date_repeat_per_month',
            'date_weekly',
            'habits',
            'habits_data_juntion',
            'habits_pomodor_junction',
            'language',
            'language_page_translation',
            'matric_column',
            'matric_junction',
            'notifications',
            'purposes',
            'refresh_tokens',
            'storage',
            'tasks',
            'Users',
        ];

        foreach ($tables as $table) {
            if ($this->tableExists($schema, $table)) {
                if (!$this->columnExists($schema, $table, 'created_at')) {
                    $this->addSql("ALTER TABLE {$table} ADD created_at DATETIME DEFAULT NULL");
                }
                if (!$this->columnExists($schema, $table, 'updated_at')) {
                    $default = 'storage' === $table ? "DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'" : 'DATETIME DEFAULT NULL';
                    $this->addSql("ALTER TABLE {$table} ADD updated_at {$default}");
                }
                if (!$this->columnExists($schema, $table, 'is_delete')) {
                    $this->addSql("ALTER TABLE {$table} ADD is_delete TINYINT(1) NOT NULL");
                }
            }
        }
    }

    public function down(Schema $schema): void
    {
        $tables = [
            'date_daily',
            'date_repeat_per_month',
            'date_weekly',
            'habits',
            'habits_data_juntion',
            'habits_pomodor_junction',
            'language',
            'language_page_translation',
            'matric_column',
            'matric_junction',
            'notifications',
            'purposes',
            'refresh_tokens',
            'storage',
            'tasks',
            'Users',
        ];

        foreach ($tables as $table) {
            if ($this->tableExists($schema, $table)) {
                if ($this->columnExists($schema, $table, 'created_at')) {
                    $this->addSql("ALTER TABLE {$table} DROP created_at");
                }
                if ($this->columnExists($schema, $table, 'updated_at')) {
                    $this->addSql("ALTER TABLE {$table} DROP updated_at");
                }
                if ($this->columnExists($schema, $table, 'is_delete')) {
                    $this->addSql("ALTER TABLE {$table} DROP is_delete");
                }
            }
        }
    }
}
