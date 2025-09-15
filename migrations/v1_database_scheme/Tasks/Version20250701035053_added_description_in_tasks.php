<?php

declare(strict_types=1);

namespace Migrations\V1\Tasks;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Adds "description" column to "tasks" table.
 */
final class Version20250701035053Addeddescriptionintasks extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adds "description" column to the "tasks" table, if it exists (PostgreSQL adaptation).';
    }

    public function up(Schema $schema): void
    {
        $sm = $this->connection->createSchemaManager();
        if ($sm->tablesExist(['tasks'])) {
            $table = $sm->introspectTable('tasks');
            $columns = $table->getColumns();

            if (!isset($columns['description'])) {
                $this->addSql('ALTER TABLE tasks ADD description TEXT');
            }
        }
    }

    public function down(Schema $schema): void
    {
        $sm = $this->connection->createSchemaManager();
        if ($sm->tablesExist(['tasks'])) {
            $table = $sm->introspectTable('tasks');
            $columns = $table->getColumns();

            if (isset($columns['description'])) {
                $this->addSql('ALTER TABLE tasks DROP COLUMN description');
            }
        }
    }
}
