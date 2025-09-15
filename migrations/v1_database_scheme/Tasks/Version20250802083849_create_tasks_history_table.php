<?php

declare(strict_types=1);

namespace Migrations\V1\Tasks;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250802083849_create_tasks_history_table extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Создание таблицы tasks_history для PostgreSQL';
    }

    public function up(Schema $schema): void
    {
        $sm = $this->connection->getSchemaManager();

        if (!$sm->tablesExist(['tasks_history'])) {
            $this->addSql('CREATE TABLE tasks_history (
                id SERIAL PRIMARY KEY,
                tasks_id INTEGER NOT NULL,
                user_id INTEGER NOT NULL,
                time_close TIMESTAMP NULL,
                time_close_month VARCHAR(255) NULL,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL,
                is_delete BOOLEAN NOT NULL
            )');


            $this->addSql('CREATE INDEX IDX_USER_ID ON tasks_history(user_id)');
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
