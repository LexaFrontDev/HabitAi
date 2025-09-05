<?php

declare(strict_types=1);

namespace Migrations\V1\Habits;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250624025658_add_data_type_column_to_habits extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Добавляет колонку data_type в таблицу habits, если она отсутствует';
    }

    public function up(Schema $schema): void
    {
        $sm = $this->connection->createSchemaManager();

        if ($sm->tablesExist(['habits']) && !$sm->introspectTable('habits')->hasColumn('data_type')) {
            $this->addSql(<<<'SQL'
                ALTER TABLE habits ADD data_type VARCHAR(255) NOT NULL
            SQL);
        }
    }

    public function down(Schema $schema): void
    {
        $sm = $this->connection->createSchemaManager();

        if ($sm->tablesExist(['habits']) && $sm->introspectTable('habits')->hasColumn('data_type')) {
            $this->addSql(<<<'SQL'
                ALTER TABLE habits DROP data_type
            SQL);
        }
    }
}
