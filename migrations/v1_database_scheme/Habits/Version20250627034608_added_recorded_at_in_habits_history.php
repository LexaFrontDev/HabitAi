<?php

declare(strict_types=1);

namespace Migrations\V1\Habits;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250627034608Addedrecordedatinhabitshistory extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Добавление поля recorded_at в таблицу habits_history с защитой от падения при отсутствии таблицы';
    }

    public function up(Schema $schema): void
    {
        $sm = $this->connection->createSchemaManager();

        if ($sm->tablesExist(['habits_history'])) {
            $columns = $sm->introspectTable('habits_history')->getColumns();

            if (!isset($columns['recorded_at'])) {
                $this->addSql('ALTER TABLE habits_history ADD recorded_at DATETIME NOT NULL');
            }
        }
    }

    public function down(Schema $schema): void
    {
        $sm = $this->connection->createSchemaManager();

        if ($sm->tablesExist(['habits_history'])) {
            $columns = $sm->introspectTable('habits_history')->getColumns();

            if (isset($columns['recorded_at'])) {
                $this->addSql('ALTER TABLE habits_history DROP recorded_at');
            }
        }
    }
}
