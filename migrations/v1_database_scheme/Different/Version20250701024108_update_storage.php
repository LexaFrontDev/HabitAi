<?php

declare(strict_types=1);

namespace Migrations\V1\Different;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250701024108Updatestorage extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Обновление таблиц storage и tasks: удаление uid, task_type; добавление полей с датами и временем (PostgreSQL совместимость)';
    }

    public function up(Schema $schema): void
    {
        $sm = $this->connection->createSchemaManager();

        if ($sm->tablesExist(['storage']) && $sm->introspectTable('storage')->hasColumn('uid')) {
            $this->addSql('ALTER TABLE storage DROP COLUMN uid');
        }

        if ($sm->tablesExist(['tasks'])) {
            $columns = $sm->introspectTable('tasks')->getColumns();

            if (isset($columns['task_type'])) {
                $this->addSql('ALTER TABLE tasks DROP COLUMN task_type');
            }

            $addColumnsSql = [];

            if (!isset($columns['date'])) {
                $addColumnsSql[] = 'ADD COLUMN date TEXT DEFAULT NULL';
            }
            if (!isset($columns['time'])) {
                $addColumnsSql[] = 'ADD COLUMN time TEXT DEFAULT NULL';
            }
            if (!isset($columns['start_date'])) {
                $addColumnsSql[] = 'ADD COLUMN start_date TEXT DEFAULT NULL';
            }
            if (!isset($columns['start_time'])) {
                $addColumnsSql[] = 'ADD COLUMN start_time TEXT DEFAULT NULL';
            }
            if (!isset($columns['end_date'])) {
                $addColumnsSql[] = 'ADD COLUMN end_date TEXT DEFAULT NULL';
            }
            if (!isset($columns['end_time'])) {
                $addColumnsSql[] = 'ADD COLUMN end_time TEXT DEFAULT NULL';
            }
            if (!isset($columns['repeat'])) {
                $addColumnsSql[] = 'ADD COLUMN "repeat" TEXT DEFAULT NULL';
            }

            if (!empty($addColumnsSql)) {
                $this->addSql('ALTER TABLE tasks ' . implode(', ', $addColumnsSql));
            }
        }
    }

    public function down(Schema $schema): void
    {
        $sm = $this->connection->createSchemaManager();

        if ($sm->tablesExist(['tasks'])) {
            $this->addSql(<<<'SQL'
                ALTER TABLE tasks
                    ADD COLUMN task_type VARCHAR(255) NOT NULL,
                    DROP COLUMN date,
                    DROP COLUMN time,
                    DROP COLUMN start_date,
                    DROP COLUMN start_time,
                    DROP COLUMN end_date,
                    DROP COLUMN end_time,
                    DROP COLUMN "repeat"
            SQL);
        }

        if ($sm->tablesExist(['storage']) && ! $sm->introspectTable('storage')->hasColumn('uid')) {
            $this->addSql('ALTER TABLE storage ADD COLUMN uid VARCHAR(100) NOT NULL');
        }
    }
}
