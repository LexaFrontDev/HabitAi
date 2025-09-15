<?php

declare(strict_types=1);

namespace Migrations\V1\Habits;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250626131625Renamemanuallycountinpurpose extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Переименование поля manually_count в auto_count в таблице purposes (PostgreSQL)';
    }

    public function up(Schema $schema): void
    {
        $sm = $this->connection->createSchemaManager();

        if ($sm->tablesExist(['purposes'])) {
            $columns = $sm->introspectTable('purposes')->getColumns();

            if (isset($columns['manually_count']) && !isset($columns['auto_count'])) {
                $this->addSql('ALTER TABLE purposes RENAME COLUMN manually_count TO auto_count');
                $this->addSql('ALTER TABLE purposes ALTER COLUMN auto_count TYPE INTEGER USING auto_count::integer');
                $this->addSql('ALTER TABLE purposes ALTER COLUMN auto_count SET NOT NULL');
            }
        }
    }

    public function down(Schema $schema): void
    {
        $sm = $this->connection->createSchemaManager();

        if ($sm->tablesExist(['purposes'])) {
            $columns = $sm->introspectTable('purposes')->getColumns();

            if (isset($columns['auto_count']) && !isset($columns['manually_count'])) {
                $this->addSql('ALTER TABLE purposes RENAME COLUMN auto_count TO manually_count');
                $this->addSql('ALTER TABLE purposes ALTER COLUMN manually_count TYPE INTEGER USING manually_count::integer');
                $this->addSql('ALTER TABLE purposes ALTER COLUMN manually_count SET NOT NULL');
            }
        }
    }
}
