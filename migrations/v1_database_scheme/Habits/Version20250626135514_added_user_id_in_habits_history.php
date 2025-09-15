<?php

declare(strict_types=1);

namespace Migrations\V1\Habits;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250626135514Addeduseridinhabitshistory extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Добавление поля user_id в таблицу habits_history (с проверкой на существование таблицы, PostgreSQL версия)';
    }

    public function up(Schema $schema): void
    {
        $sm = $this->connection->createSchemaManager();

        if ($sm->tablesExist(['habits_history'])) {
            $columns = $sm->introspectTable('habits_history')->getColumns();

            if (!isset($columns['user_id'])) {
                $this->addSql(<<<'SQL'
                    ALTER TABLE habits_history ADD COLUMN user_id INTEGER NOT NULL
                SQL);
            }
        }
    }

    public function down(Schema $schema): void
    {
        $sm = $this->connection->createSchemaManager();

        if ($sm->tablesExist(['habits_history'])) {
            $columns = $sm->introspectTable('habits_history')->getColumns();

            if (isset($columns['user_id'])) {
                $this->addSql(<<<'SQL'
                    ALTER TABLE habits_history DROP COLUMN user_id
                SQL);
            }
        }
    }
}
