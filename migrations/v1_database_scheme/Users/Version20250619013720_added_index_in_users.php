<?php

declare(strict_types=1);

namespace Migrations\V1\Users;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250619013720_added_index_in_users extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Добавление уникальных индексов на поля name и email в таблице Users, с проверкой наличия перед созданием';
    }

    public function up(Schema $schema): void
    {
        $schemaManager = $this->connection->createSchemaManager();

        if ($schemaManager->tablesExist(['Users'])) {
            $indexes = $schemaManager->listTableIndexes('Users');
            $existingIndexNames = array_map(fn($index) => strtolower($index->getName()), $indexes);

            if (!in_array('uniq_1483a5e95e237e06', $existingIndexNames, true)) {
                $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E95E237E06 ON Users (name)');
            }

            if (!in_array('uniq_1483a5e9e7927c74', $existingIndexNames, true)) {
                $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9E7927C74 ON Users (email)');
            }
        }
    }


    public function down(Schema $schema): void
    {
        $schemaManager = $this->connection->createSchemaManager();

        if ($schemaManager->tablesExist(['Users'])) {
            $indexes = $schemaManager->listTableIndexes('Users');
            $existingIndexNames = array_map(fn($index) => strtolower($index->getName()), $indexes);

            if (in_array('uniq_1483a5e95e237e06', $existingIndexNames, true)) {
                $this->addSql('DROP INDEX UNIQ_1483A5E95E237E06 ON Users');
            }

            if (in_array('uniq_1483a5e9e7927c74', $existingIndexNames, true)) {
                $this->addSql('DROP INDEX UNIQ_1483A5E9E7927C74 ON Users');
            }
        }
    }

}
