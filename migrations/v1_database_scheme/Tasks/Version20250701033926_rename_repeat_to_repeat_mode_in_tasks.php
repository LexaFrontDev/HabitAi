<?php

declare(strict_types=1);

namespace Migrations\V1\Tasks;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Renames the "repeat" column to "repeat_mode" in the Tasks table.
 */
final class Version20250701033926Renamerepeattorepeatmodeintasks extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Renames "repeat" column to "repeat_mode" in the Tasks table, if it exists.';
    }

    public function up(Schema $schema): void
    {
        $sm = $this->connection->createSchemaManager();
        if ($sm->tablesExist(['Tasks'])) {
            $table = $sm->introspectTable('Tasks');
            $columns = $table->getColumns();

            if (isset($columns['repeat']) && !isset($columns['repeat_mode'])) {
                $this->addSql('ALTER TABLE Tasks CHANGE `repeat` repeat_mode VARCHAR(255) DEFAULT NULL');
            }
        }
    }

    public function down(Schema $schema): void
    {
        $sm = $this->connection->createSchemaManager();
        if ($sm->tablesExist(['Tasks'])) {
            $table = $sm->introspectTable('Tasks');
            $columns = $table->getColumns();

            if (isset($columns['repeat_mode']) && !isset($columns['repeat'])) {
                $this->addSql('ALTER TABLE Tasks CHANGE repeat_mode `repeat` VARCHAR(255) DEFAULT NULL');
            }
        }
    }

}
