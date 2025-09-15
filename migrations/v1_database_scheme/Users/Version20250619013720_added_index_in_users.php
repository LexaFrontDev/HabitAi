<?php

declare(strict_types=1);

namespace Migrations\V1\Users;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250619013720_added_index_in_users extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Добавление уникальных индексов на поля name и email в таблице users (PostgreSQL адаптация)';
    }

    public function up(Schema $schema): void
    {
        $schemaManager = $this->connection->createSchemaManager();

        if ($schemaManager->tablesExist(['users'])) {
            $indexes = $schemaManager->listTableIndexes('users');
            $existingIndexNames = array_map(fn ($index) => strtolower($index->getName()), $indexes);

            if (!in_array('uniq_1483a5e95e237e06', $existingIndexNames, true)) {
                $this->addSql('CREATE UNIQUE INDEX uniq_1483a5e95e237e06 ON users (name)');
            }

            if (!in_array('uniq_1483a5e9e7927c74', $existingIndexNames, true)) {
                $this->addSql('CREATE UNIQUE INDEX uniq_1483a5e9e7927c74 ON users (email)');
            }
        }
    }

    public function down(Schema $schema): void
    {
        $schemaManager = $this->connection->createSchemaManager();

        if ($schemaManager->tablesExist(['users'])) {
            $indexes = $schemaManager->listTableIndexes('users');
            $existingIndexNames = array_map(fn ($index) => strtolower($index->getName()), $indexes);

            if (in_array('uniq_1483a5e95e237e06', $existingIndexNames, true)) {
                $this->addSql('DROP INDEX uniq_1483a5e95e237e06');
            }

            if (in_array('uniq_1483a5e9e7927c74', $existingIndexNames, true)) {
                $this->addSql('DROP INDEX uniq_1483a5e9e7927c74');
            }
        }
    }
}
