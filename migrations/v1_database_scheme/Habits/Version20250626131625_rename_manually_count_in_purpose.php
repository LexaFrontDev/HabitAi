<?php

declare(strict_types=1);

namespace Migrations\V1\Habits;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250626131625Renamemanuallycountinpurpose extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Переименование поля manually_count в auto_count в таблице purposes (с проверкой на существование)';
    }

    public function up(Schema $schema): void
    {
        $sm = $this->connection->createSchemaManager();

        if ($sm->tablesExist(['purposes'])) {
            $columns = $sm->introspectTable('purposes')->getColumns();

            if (isset($columns['manually_count']) && !isset($columns['auto_count'])) {
                $this->addSql('ALTER TABLE purposes CHANGE manually_count auto_count INT NOT NULL');
            }
        }
    }

    public function down(Schema $schema): void
    {
        $sm = $this->connection->createSchemaManager();

        if ($sm->tablesExist(['purposes'])) {
            $columns = $sm->introspectTable('purposes')->getColumns();

            if (isset($columns['auto_count']) && !isset($columns['manually_count'])) {
                $this->addSql('ALTER TABLE purposes CHANGE auto_count manually_count INT NOT NULL');
            }
        }
    }

}
