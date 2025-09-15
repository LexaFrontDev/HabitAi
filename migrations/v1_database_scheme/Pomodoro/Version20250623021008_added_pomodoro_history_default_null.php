<?php

declare(strict_types=1);

namespace Migrations\V1\Pomodoro;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250623021008_added_pomodoro_history_default_null extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Изменение target_id в pomodoro_history: сделать DEFAULT NULL (PostgreSQL)';
    }

    public function up(Schema $schema): void
    {
        $sm = $this->connection->createSchemaManager();

        if ($sm->tablesExist(['pomodoro_history'])) {
            $columns = $sm->introspectTable('pomodoro_history')->getColumns();

            if (isset($columns['target_id'])) {
                $this->addSql('ALTER TABLE pomodoro_history ALTER COLUMN target_id DROP NOT NULL');
                $this->addSql('ALTER TABLE pomodoro_history ALTER COLUMN target_id SET DEFAULT NULL');
            }
        }
    }

    public function down(Schema $schema): void
    {
        $sm = $this->connection->createSchemaManager();

        if ($sm->tablesExist(['pomodoro_history'])) {
            $columns = $sm->introspectTable('pomodoro_history')->getColumns();

            if (isset($columns['target_id'])) {
                $this->addSql('ALTER TABLE pomodoro_history ALTER COLUMN target_id SET NOT NULL');
                $this->addSql('ALTER TABLE pomodoro_history ALTER COLUMN target_id DROP DEFAULT');
            }
        }
    }
}
