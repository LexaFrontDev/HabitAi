<?php

declare(strict_types=1);

namespace Migrations\V1\Different;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250701024108Updatestorage extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Обновление таблиц storage и tasks: удаление uid, task_type; добавление полей с датами и временем';
    }

    public function up(Schema $schema): void
    {
        $sm = $this->connection->createSchemaManager();

        if ($sm->tablesExist(['storage']) && $sm->introspectTable('storage')->hasColumn('uid')) {
            $this->addSql('ALTER TABLE storage DROP uid');
        }

        if ($sm->tablesExist(['tasks'])) {
            $columns = $sm->introspectTable('tasks')->getColumns();

            if (isset($columns['task_type'])) {
                $this->addSql('ALTER TABLE tasks DROP task_type');
            }

            $addColumnsSql = [];

            if (!isset($columns['date'])) {
                $addColumnsSql[] = 'ADD date VARCHAR(255) DEFAULT NULL';
            }
            if (!isset($columns['time'])) {
                $addColumnsSql[] = 'ADD time VARCHAR(255) DEFAULT NULL';
            }
            if (!isset($columns['start_date'])) {
                $addColumnsSql[] = 'ADD start_date VARCHAR(255) DEFAULT NULL';
            }
            if (!isset($columns['start_time'])) {
                $addColumnsSql[] = 'ADD start_time VARCHAR(255) DEFAULT NULL';
            }
            if (!isset($columns['end_date'])) {
                $addColumnsSql[] = 'ADD end_date VARCHAR(255) DEFAULT NULL';
            }
            if (!isset($columns['end_time'])) {
                $addColumnsSql[] = 'ADD end_time VARCHAR(255) DEFAULT NULL';
            }
            if (!isset($columns['repeat'])) {
                $addColumnsSql[] = 'ADD `repeat` VARCHAR(255) DEFAULT NULL';
            }

            if (!empty($addColumnsSql)) {
                $this->addSql('ALTER TABLE tasks '.implode(', ', $addColumnsSql));
            }
        }
    }

    public function down(Schema $schema): void
    {
        $sm = $this->connection->createSchemaManager();

        if ($sm->tablesExist(['tasks'])) {
            $this->addSql(<<<'SQL'
                ALTER TABLE tasks
                    ADD task_type VARCHAR(255) NOT NULL,
                    DROP date,
                    DROP time,
                    DROP start_date,
                    DROP start_time,
                    DROP end_date,
                    DROP end_time,
                    DROP `repeat`
            SQL);
        }

        if ($sm->tablesExist(['storage']) && ! $sm->introspectTable('storage')->hasColumn('uid')) {
            $this->addSql('ALTER TABLE storage ADD uid VARCHAR(100) NOT NULL');
        }
    }
}
