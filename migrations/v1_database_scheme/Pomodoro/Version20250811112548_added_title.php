<?php

declare(strict_types=1);

namespace Migrations\V1\Pomodoro;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250811112548AddedTitle extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Добавляет колонку title в таблицу pomodoro_history, если её нет (PostgreSQL совместимость)';
    }

    public function up(Schema $schema): void
    {
        $sm = $this->connection->createSchemaManager();

        if ($sm->tablesExist(['pomodoro_history'])) {
            $columns = $sm->introspectTable('pomodoro_history')->getColumns();

            if (!isset($columns['title'])) {
                $this->addSql('ALTER TABLE pomodoro_history ADD COLUMN title VARCHAR(120) NOT NULL');
            }
        }
    }

    public function down(Schema $schema): void
    {
        $sm = $this->connection->createSchemaManager();

        if ($sm->tablesExist(['pomodoro_history'])) {
            $columns = $sm->introspectTable('pomodoro_history')->getColumns();

            if (isset($columns['title'])) {
                $this->addSql('ALTER TABLE pomodoro_history DROP COLUMN title');
            }
        }
    }
}
