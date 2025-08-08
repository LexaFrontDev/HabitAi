<?php

declare(strict_types=1);

namespace Migrations\V1\Habits;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250626121151Addedmanuallycountinpurpose extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Добавление поля manually_count в таблицу purposes (с проверкой на существование таблицы)';
    }

    public function up(Schema $schema): void
    {
        $sm = $this->connection->createSchemaManager();

        if ($sm->tablesExist(['purposes'])) {
            $this->addSql(<<<'SQL'
                ALTER TABLE purposes ADD manually_count INT NOT NULL
            SQL);
        }
    }

    public function down(Schema $schema): void
    {
        $sm = $this->connection->createSchemaManager();

        if ($sm->tablesExist(['purposes'])) {
            $this->addSql(<<<'SQL'
                ALTER TABLE purposes DROP manually_count
            SQL);
        }
    }
}
