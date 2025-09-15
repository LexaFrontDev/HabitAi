<?php

declare(strict_types=1);

namespace Migrations\V1\Tasks;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Renames the "repeat" column to "repeat_mode" in the tasks table.
 */
final class Version20250701033926Renamerepeattorepeatmodeintasks extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Renames "repeat" column to "repeat_mode" in the tasks table, if it exists (PostgreSQL adaptation).';
    }

    public function up(Schema $schema): void
    {
        $sm = $this->connection->createSchemaManager();
        if ($sm->tablesExist(['tasks'])) {
            $table = $sm->introspectTable('tasks');
            $columns = $table->getColumns();

            if (isset($columns['repeat']) && !isset($columns['repeat_mode'])) {
                $this->addSql('ALTER TABLE tasks RENAME COLUMN "repeat" TO repeat_mode');
            }
        }
    }

    public function down(Schema $schema): void
    {
        $sm = $this->connection->createSchemaManager();
        if ($sm->tablesExist(['tasks'])) {
            $table = $sm->introspectTable('tasks');
            $columns = $table->getColumns();

            if (isset($columns['repeat_mode']) && !isset($columns['repeat'])) {
                $this->addSql('ALTER TABLE tasks RENAME COLUMN repeat_mode TO "repeat"');
            }
        }
    }
}
