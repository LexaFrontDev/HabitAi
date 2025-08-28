<?php

declare(strict_types=1);

namespace Migrations\V1\Tasks;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Adds user_id column to Tasks table, if the table exists.
 */
final class Version20250701030734Addeduseridintasks extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adds user_id column to Tasks table if it exists';
    }

    public function up(Schema $schema): void
    {
        $sm = $this->connection->createSchemaManager();

        if ($sm->tablesExist(['Tasks'])) {
            $columns = $sm->introspectTable('Tasks')->getColumns();

            if (!isset($columns['user_id'])) {
                $this->addSql('ALTER TABLE Tasks ADD user_id INT NOT NULL');
            }
        }
    }

    public function down(Schema $schema): void
    {
        $sm = $this->connection->createSchemaManager();

        if ($sm->tablesExist(['Tasks'])) {
            $columns = $sm->introspectTable('Tasks')->getColumns();

            if (isset($columns['user_id'])) {
                $this->addSql('ALTER TABLE Tasks DROP user_id');
            }
        }
    }
}
