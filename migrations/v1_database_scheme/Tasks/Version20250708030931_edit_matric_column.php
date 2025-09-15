<?php

declare(strict_types=1);

namespace Migrations\V1\Tasks;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Adds user_id column to matric_column table
 */
final class Version20250708030931Editmatriccolumn extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add user_id column to matric_column table (PostgreSQL adaptation)';
    }

    public function up(Schema $schema): void
    {
        $sm = $this->connection->createSchemaManager();

        if ($sm->tablesExist(['matric_column'])) {
            $table = $sm->introspectTable('matric_column');
            $columns = $table->getColumns();

            if (!isset($columns['user_id'])) {
                $this->addSql('ALTER TABLE matric_column ADD user_id INTEGER NOT NULL');
            }
        }
    }

    public function down(Schema $schema): void
    {
        $sm = $this->connection->createSchemaManager();

        if ($sm->tablesExist(['matric_column'])) {
            $table = $sm->introspectTable('matric_column');
            $columns = $table->getColumns();

            if (isset($columns['user_id'])) {
                $this->addSql('ALTER TABLE matric_column DROP COLUMN user_id');
            }
        }
    }
}
