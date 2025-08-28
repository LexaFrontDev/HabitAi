<?php

declare(strict_types=1);

namespace Migrations\V1\Tasks;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Adds "description" column to "Tasks" table.
 */
final class Version20250701035053Addeddescriptionintasks extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adds "description" column to the "Tasks" table, if it exists.';
    }

    public function up(Schema $schema): void
    {
        $sm = $this->connection->createSchemaManager();
        if ($sm->tablesExist(['Tasks'])) {
            $table = $sm->introspectTable('Tasks');
            $columns = $table->getColumns();

            if (!isset($columns['description'])) {
                $this->addSql('ALTER TABLE Tasks ADD description LONGTEXT DEFAULT NULL');
            }
        }
    }

    public function down(Schema $schema): void
    {
        $sm = $this->connection->createSchemaManager();
        if ($sm->tablesExist(['Tasks'])) {
            $table = $sm->introspectTable('Tasks');
            $columns = $table->getColumns();

            if (isset($columns['description'])) {
                $this->addSql('ALTER TABLE Tasks DROP description');
            }
        }
    }
}
