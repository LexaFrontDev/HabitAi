<?php

declare(strict_types=1);

namespace Migrations\V1\Tasks;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Adds user_id column to tasks table, if the table exists.
 */
final class Version20250701030734Addeduseridintasks extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adds user_id column to tasks table if it exists';
    }

    public function up(Schema $schema): void
    {
        $sm = $this->connection->createSchemaManager();

        if ($sm->tablesExist(['tasks'])) {
            $columns = $sm->introspectTable('tasks')->getColumns();

            if (!isset($columns['user_id'])) {
                $this->addSql('ALTER TABLE tasks ADD user_id INT NOT NULL');
            }
        }
    }

    public function down(Schema $schema): void
    {
        $sm = $this->connection->createSchemaManager();

        if ($sm->tablesExist(['tasks'])) {
            $columns = $sm->introspectTable('tasks')->getColumns();

            if (isset($columns['user_id'])) {
                $this->addSql('ALTER TABLE tasks DROP user_id');
            }
        }
    }
}
